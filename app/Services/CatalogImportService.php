<?php

namespace App\Services;

use App\Models\Famille;
use App\Models\ImportCatalogue;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CatalogImportService
{
    /**
     * Dictionnaire d'équivalences de familles standards.
     */
    protected array $familleSynonyms = [
        'optique' => 'OPTIQUES',
        'optiques' => 'OPTIQUES',
        'phare' => 'OPTIQUES',
        'phares' => 'OPTIQUES',
        'feu av' => 'OPTIQUES',
        'feux av' => 'OPTIQUES',
        'pare choc' => 'PARE-CHOCS',
        'pare-chocs' => 'PARE-CHOCS',
        'parechoc' => 'PARE-CHOCS',
        'parechocs' => 'PARE-CHOCS',
        'bouclier' => 'PARE-CHOCS',
        'feu ar' => 'FEUX ARRIÈRE',
        'feux ar' => 'FEUX ARRIÈRE',
        'feu arriere' => 'FEUX ARRIÈRE',
        'feux arriere' => 'FEUX ARRIÈRE',
        'feu arriere' => 'FEUX ARRIÈRE',
        'feux arrières' => 'FEUX ARRIÈRE',
        'calandre' => 'CALANDRES',
        'calandres' => 'CALANDRES',
        'grille' => 'GRILLES',
        'grilles' => 'GRILLES',
        'spoiler' => 'SPOILERS',
        'spoilers' => 'SPOILERS',
        'lame' => 'SPOILERS',
        'retroviseur' => 'RÉTROVISEURS',
        'retroviseurs' => 'RÉTROVISEURS',
        'rétroviseur' => 'RÉTROVISEURS',
        'rétroviseurs' => 'RÉTROVISEURS',
        'glace' => 'RÉTROVISEURS',
        'ailes' => 'AILES',
        'aile' => 'AILES',
        'capot' => 'CAPOTS',
        'capots' => 'CAPOTS',
    ];

    /**
     * Exécuter l'importation à partir d'un fichier téléchargé ou d'un chemin local.
     */
    public function import(string|UploadedFile $file, ?User $user = null, ?string $originalFilename = null): ImportCatalogue
    {
        $filePath = is_string($file) ? $file : $file->getRealPath();
        $filename = $originalFilename ?: (is_string($file) ? basename($file) : $file->getClientOriginalName());

        $fileType = IOFactory::identify($filePath);
        $reader = IOFactory::createReader($fileType);

        if ($fileType === 'Csv') {
            $handle = fopen($filePath, 'r');
            $sample = $handle ? (fgets($handle) ?: '') : '';
            if ($handle) fclose($handle);

            $semicolons = substr_count($sample, ';');
            $commas = substr_count($sample, ',');
            $tabs = substr_count($sample, "\t");

            if ($semicolons > $commas && $semicolons > $tabs) {
                $reader->setDelimiter(';');
            } elseif ($tabs > $commas) {
                $reader->setDelimiter("\t");
            } else {
                $reader->setDelimiter(',');
            }
            $reader->setInputEncoding('UTF-8');
        }

        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return ImportCatalogue::create([
                'user_id' => $user?->id,
                'fichier' => $filename,
                'date_import' => now(),
                'nb_lignes' => 0,
                'nb_crees' => 0,
                'nb_mis_a_jour' => 0,
                'nb_erreurs' => 1,
                'rapport_erreurs_json' => [
                    ['ligne' => 1, 'designation' => 'N/A', 'motif' => 'Fichier vide ou sans contenu lisible.', 'donnees' => []]
                ],
                'statut' => 'erreur',
            ]);
        }

        // 1. Détection des en-têtes
        $headerRowKey = key($rows);
        $rawHeaders = array_shift($rows);
        $headerMap = $this->resolveHeaderMap($rawHeaders);

        $nbCrees = 0;
        $nbMisAJour = 0;
        $errors = [];
        $lineNumber = 1; // 1 était l'en-tête

        // Pré-chargement des référentiels en mémoire pour optimiser la vitesse
        $marquesCache = Marque::all();
        $vehiculesCache = Vehicule::with('marque')->get();
        $famillesCache = Famille::all();

        foreach ($rows as $row) {
            $lineNumber++;

            // Ignorer les lignes totalement vides
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $mapped = $this->extractRowData($row, $headerMap);

            // Validation de base : Désignation obligatoire
            if (empty($mapped['designation'])) {
                $errors[] = [
                    'ligne' => $lineNumber,
                    'designation' => 'N/A',
                    'motif' => 'Désignation de la pièce manquante ou vide.',
                    'donnees' => $this->sanitizeRowForLog($row),
                ];
                continue;
            }

            // Validation de base : Prix TTC positif
            $prixTtc = $this->parsePrice($mapped['prix_ttc']);
            if ($prixTtc === null || $prixTtc <= 0) {
                $errors[] = [
                    'ligne' => $lineNumber,
                    'designation' => $mapped['designation'],
                    'motif' => "Prix TTC invalide ou absent ('" . ($mapped['prix_ttc'] ?? '') . "'). Un montant positif est requis.",
                    'donnees' => $this->sanitizeRowForLog($row),
                ];
                continue;
            }

            // Normalisation de la Position
            $position = $this->determinePosition($mapped['position'], $mapped['designation']);

            // Déduction / Résolution de l'Année
            $anneeDebut = $this->determineYear($mapped['annee'], $mapped['designation']);

            // Résolution de la Marque et du Véhicule
            $vehicule = $this->resolveVehicule($mapped['vehicule'], $mapped['marque'], $mapped['designation'], $vehiculesCache, $marquesCache);

            // Résolution ou création de la Famille
            $famille = $this->resolveFamille($mapped['famille'], $mapped['designation'], $famillesCache);

            // Variante
            $variante = !empty($mapped['variante']) ? trim($mapped['variante']) : null;

            // Détermination des stocks / côtés
            $isRL = ($position === 'RL');
            $dispoDroite = $isRL ? $this->parseBoolean($mapped['dispo_droite'], true) : null;
            $dispoGauche = $isRL ? $this->parseBoolean($mapped['dispo_gauche'], true) : null;
            $enStock = !$isRL ? $this->parseBoolean($mapped['en_stock'], true) : null;

            // Clé d'unicité logique : désignation + vehicule_id + position + annee_debut
            $cleanDesignation = trim($mapped['designation']);
            $existing = Piece::where('designation', $cleanDesignation)
                ->where('vehicule_id', $vehicule?->id)
                ->where('position', $position)
                ->where('annee_debut', $anneeDebut)
                ->first();

            if ($existing) {
                // Mise à jour de la pièce existante
                $existing->prix_ttc = $prixTtc;
                $existing->famille_id = $famille->id;
                if ($variante) {
                    $existing->variante = $variante;
                }
                if ($isRL) {
                    $existing->dispo_droite = $dispoDroite;
                    $existing->dispo_gauche = $dispoGauche;
                    $existing->en_stock = null;
                } else {
                    $existing->en_stock = $enStock;
                    $existing->dispo_droite = null;
                    $existing->dispo_gauche = null;
                }
                $existing->actif = true;
                $existing->save();

                $nbMisAJour++;
            } else {
                // Création d'une nouvelle pièce
                Piece::create([
                    'designation' => $cleanDesignation,
                    'famille_id' => $famille->id,
                    'vehicule_id' => $vehicule?->id,
                    'annee_debut' => $anneeDebut,
                    'position' => $position,
                    'variante' => $variante,
                    'prix_ttc' => $prixTtc,
                    'dispo_droite' => $dispoDroite,
                    'dispo_gauche' => $dispoGauche,
                    'en_stock' => $enStock,
                    'actif' => true,
                ]);

                $nbCrees++;
            }
        }

        $totalTraite = $nbCrees + $nbMisAJour + count($errors);

        return ImportCatalogue::create([
            'user_id' => $user?->id,
            'fichier' => $filename,
            'date_import' => now(),
            'nb_lignes' => $totalTraite,
            'nb_crees' => $nbCrees,
            'nb_mis_a_jour' => $nbMisAJour,
            'nb_erreurs' => count($errors),
            'rapport_erreurs_json' => $errors,
            'statut' => count($errors) > 0 ? (count($errors) === $totalTraite ? 'echec' : 'partiel') : 'termine',
        ]);
    }

    /**
     * Mapper les colonnes détectées aux champs standard.
     */
    protected function resolveHeaderMap(array $rawHeaders): array
    {
        $map = [
            'marque' => null,
            'vehicule' => null,
            'annee' => null,
            'famille' => null,
            'designation' => null,
            'position' => null,
            'variante' => null,
            'prix_ttc' => null,
            'dispo_droite' => null,
            'dispo_gauche' => null,
            'en_stock' => null,
        ];

        foreach ($rawHeaders as $colIndex => $headerText) {
            if (!$headerText) continue;

            $normalized = $this->normalizeString((string)$headerText);

            if (!$map['marque'] && preg_match('/^(marque|constructeur|brand|make)$/', $normalized)) {
                $map['marque'] = $colIndex;
            } elseif (!$map['vehicule'] && preg_match('/^(modele|vehicule|model|auto)$/', $normalized)) {
                $map['vehicule'] = $colIndex;
            } elseif (!$map['annee'] && preg_match('/^(annee|anneedebut|anneemodele|year)$/', $normalized)) {
                $map['annee'] = $colIndex;
            } elseif (!$map['famille'] && preg_match('/^(famille|categorie|category|family|rubrique)$/', $normalized)) {
                $map['famille'] = $colIndex;
            } elseif (!$map['designation'] && preg_match('/^(designation|libelle|description|piece|nom)$/', $normalized)) {
                $map['designation'] = $colIndex;
            } elseif (!$map['position'] && preg_match('/^(position|cote|side|emplacement)$/', $normalized)) {
                $map['position'] = $colIndex;
            } elseif (!$map['variante'] && preg_match('/^(variante|precision|detail|remarque)$/', $normalized)) {
                $map['variante'] = $colIndex;
            } elseif (!$map['prix_ttc'] && preg_match('/^(prixttc|prix|tarif|price|montant)$/', $normalized)) {
                $map['prix_ttc'] = $colIndex;
            } elseif (!$map['dispo_droite'] && preg_match('/^(dispodroite|droite|droit|stockd)$/', $normalized)) {
                $map['dispo_droite'] = $colIndex;
            } elseif (!$map['dispo_gauche'] && preg_match('/^(dispogauche|gauche|stockg)$/', $normalized)) {
                $map['dispo_gauche'] = $colIndex;
            } elseif (!$map['en_stock'] && preg_match('/^(enstock|stock|disponible|dispo)$/', $normalized)) {
                $map['en_stock'] = $colIndex;
            }
        }

        // Fallbacks intuitifs si certains headers n'ont pas matché exactement
        if (!$map['designation']) {
            foreach ($rawHeaders as $colIndex => $headerText) {
                $norm = $this->normalizeString((string)$headerText);
                if (str_contains($norm, 'design') || str_contains($norm, 'piece') || str_contains($norm, 'libel')) {
                    $map['designation'] = $colIndex;
                    break;
                }
            }
        }

        if (!$map['prix_ttc']) {
            foreach ($rawHeaders as $colIndex => $headerText) {
                $norm = $this->normalizeString((string)$headerText);
                if (str_contains($norm, 'prix') || str_contains($norm, 'tarif')) {
                    $map['prix_ttc'] = $colIndex;
                    break;
                }
            }
        }

        return $map;
    }

    /**
     * Extraire les données d'une ligne selon le mapping.
     */
    protected function extractRowData(array $row, array $headerMap): array
    {
        $data = [];
        foreach ($headerMap as $field => $colIndex) {
            $data[$field] = ($colIndex && isset($row[$colIndex])) ? trim((string)$row[$colIndex]) : null;
        }
        return $data;
    }

    /**
     * Déterminer la position (AV, AR, CTRL, RL).
     */
    protected function determinePosition(?string $posText, string $designation): string
    {
        if ($posText) {
            $norm = $this->normalizeString($posText);
            if (in_array($norm, ['av', 'avant', 'front'])) return 'AV';
            if (in_array($norm, ['ar', 'arriere', 'rear', 'back'])) return 'AR';
            if (in_array($norm, ['ctrl', 'central', 'centre', 'milieu'])) return 'CTRL';
            if (in_array($norm, ['rl', 'droitgauche', 'droit', 'gauche', 'paire', 'lesdeux', 'gd', 'dg'])) return 'RL';
        }

        // Déduction depuis la désignation
        $desNorm = ' ' . strtoupper($designation) . ' ';
        if (preg_match('/\b(RL|D\/G|G\/D|DROIT|GAUCHE)\b/', $desNorm)) {
            return 'RL';
        }
        if (preg_match('/\b(AV|AVANT|FRONT)\b/', $desNorm)) {
            return 'AV';
        }
        if (preg_match('/\b(AR|ARRIERE|ARRIÈRE|REAR)\b/', $desNorm)) {
            return 'AR';
        }
        if (preg_match('/\b(CTRL|CENTRAL|CENTRE)\b/', $desNorm)) {
            return 'CTRL';
        }

        return 'AV'; // Défaut carrosserie avant
    }

    /**
     * Déterminer l'année de compatibilité début.
     */
    protected function determineYear(?string $anneeText, string $designation): int
    {
        if ($anneeText) {
            // Nettoyage des chiffres
            if (preg_match('/(19\d{2}|20\d{2})/', $anneeText, $matches)) {
                return (int)$matches[1];
            }
            if (preg_match('/^(\d{2})$/', trim($anneeText), $matches)) {
                $yy = (int)$matches[1];
                return $yy > 50 ? 1900 + $yy : 2000 + $yy;
            }
        }

        // Détection dans la désignation : 4 chiffres (ex. 2013, 2021)
        if (preg_match('/\b(199\d|20[0-3]\d)\b/', $designation, $matches)) {
            return (int)$matches[1];
        }

        // Détection format abrégé type '13-16' ou '12-' ou '18+' ou '20->'
        if (preg_match('/\b(\d{2})\s*[-–>+]\s*(\d{2})?\b/', $designation, $matches)) {
            $yy = (int)$matches[1];
            if ($yy >= 90 || $yy <= 35) {
                return $yy >= 90 ? 1900 + $yy : 2000 + $yy;
            }
        }

        return 2015; // Année médiane par défaut si non spécifiée
    }

    /**
     * Résoudre le Véhicule (ou null si "À classer").
     */
    protected function resolveVehicule(?string $vehiculeText, ?string $marqueText, string $designation, Collection $vehiculesCache, Collection $marquesCache): ?Vehicule
    {
        // 1. Essai de correspondance directe si la colonne modèle/véhicule est renseignée
        if ($vehiculeText && trim($vehiculeText) !== '') {
            $vTarget = $this->normalizeWords($vehiculeText);
            
            // Priorité au modèle rattaché à la marque si spécifiée
            $marque = $this->resolveMarque($marqueText, $designation, $marquesCache);

            $directMatch = $vehiculesCache->first(function ($v) use ($vTarget, $marque) {
                $matchName = ($this->normalizeWords($v->nom) === $vTarget);
                return $matchName && (!$marque || $v->marque_id === $marque->id);
            });

            if (!$directMatch) {
                $directMatch = $vehiculesCache->first(fn ($v) => $this->normalizeWords($v->nom) === $vTarget);
            }

            if ($directMatch) {
                return $directMatch;
            }
        }

        // 2. Recherche dans le texte global (modèle + désignation)
        $searchWords = ' ' . $this->normalizeWords(($vehiculeText ?? '') . ' ' . $designation) . ' ';
        $sortedVehicules = $vehiculesCache->sortByDesc(fn ($v) => strlen($v->nom));

        foreach ($sortedVehicules as $vehicule) {
            $vWords = ' ' . $this->normalizeWords($vehicule->nom) . ' ';
            if (str_contains($searchWords, $vWords)) {
                return $vehicule;
            }
        }

        // 3. Si le modèle est explicitement renseigné et la marque identifiée, créer le modèle si inexistant
        if ($vehiculeText && trim($vehiculeText) !== '') {
            $marque = $marque ?? $this->resolveMarque($marqueText, $designation, $marquesCache);
            if ($marque) {
                $nouveauNom = ucwords(strtolower(trim($vehiculeText)));
                $nouveauVehicule = Vehicule::firstOrCreate(
                    ['marque_id' => $marque->id, 'nom' => $nouveauNom],
                    ['slug' => Str::slug($nouveauNom)]
                );
                if (!$vehiculesCache->contains('id', $nouveauVehicule->id)) {
                    $vehiculesCache->push($nouveauVehicule);
                }
                return $nouveauVehicule;
            }
        }

        // Si aucun véhicule n'est reconnu -> null (pièce "À classer")
        return null;
    }

    /**
     * Résoudre la marque.
     */
    protected function resolveMarque(?string $marqueText, string $designation, Collection $marquesCache): ?Marque
    {
        if ($marqueText && trim($marqueText) !== '') {
            $mTarget = $this->normalizeWords($marqueText);
            foreach ($marquesCache as $marque) {
                if ($this->normalizeWords($marque->nom) === $mTarget) {
                    return $marque;
                }
            }
        }

        // Chercher une marque connue dans la désignation
        $desWords = ' ' . $this->normalizeWords($designation) . ' ';
        foreach ($marquesCache as $marque) {
            $mWords = ' ' . $this->normalizeWords($marque->nom) . ' ';
            if (str_contains($desWords, $mWords)) {
                return $marque;
            }
        }

        return null;
    }

    /**
     * Normaliser une phrase en conservant les espaces entre les mots.
     */
    protected function normalizeWords(string $str): string
    {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        $transliterator = @transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $str);
        if ($transliterator !== false) {
            $str = $transliterator;
        } else {
            $str = strtr(utf8_decode($str), 
                utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 
                'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        }
        $str = preg_replace('/[^a-z0-9]+/', ' ', $str);
        return trim((string)$str);
    }

    /**
     * Résoudre ou créer automatiquement la famille.
     */
    protected function resolveFamille(?string $familleText, string $designation, Collection $famillesCache): Famille
    {
        // 1. Essai avec le texte de la colonne famille
        if ($familleText) {
            $norm = $this->normalizeString($familleText);

            // Vérifier dans le dictionnaire de synonymes
            if (isset($this->familleSynonyms[$norm])) {
                $targetName = $this->familleSynonyms[$norm];
                $existing = $famillesCache->first(fn ($f) => $this->normalizeString($f->nom) === $this->normalizeString($targetName));
                if ($existing) return $existing;
            }

            // Vérifier dans la base existante
            foreach ($famillesCache as $famille) {
                if ($this->normalizeString($famille->nom) === $norm) {
                    return $famille;
                }
            }

            // Créer la nouvelle famille
            $nomMaj = mb_strtoupper(trim($familleText), 'UTF-8');
            $created = Famille::create([
                'nom' => $nomMaj,
                'slug' => Str::slug($nomMaj),
            ]);
            $famillesCache->push($created);
            return $created;
        }

        // 2. Détection via mots clés dans la désignation
        $desNorm = $this->normalizeString($designation);
        foreach ($this->familleSynonyms as $keyword => $canonicalName) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $desNorm)) {
                $existing = $famillesCache->first(fn ($f) => $this->normalizeString($f->nom) === $this->normalizeString($canonicalName));
                if ($existing) return $existing;

                $created = Famille::create([
                    'nom' => $canonicalName,
                    'slug' => Str::slug($canonicalName),
                ]);
                $famillesCache->push($created);
                return $created;
            }
        }

        // 3. Fallback "DIVERS"
        $divers = $famillesCache->first(fn ($f) => $f->slug === 'divers' || $this->normalizeString($f->nom) === 'divers');
        if ($divers) return $divers;

        $createdDivers = Famille::create([
            'nom' => 'DIVERS',
            'slug' => 'divers',
        ]);
        $famillesCache->push($createdDivers);
        return $createdDivers;
    }

    /**
     * Parser le prix numérique depuis une chaîne brute (ex. "1 250,50 DH", "450.00").
     */
    protected function parsePrice(mixed $rawPrice): ?float
    {
        if ($rawPrice === null || $rawPrice === '') {
            return null;
        }

        if (is_numeric($rawPrice)) {
            return round((float)$rawPrice, 2);
        }

        $str = (string)$rawPrice;
        // Remplacer virgule par point, supprimer les espaces et lettres (MAD, DH, etc.)
        $str = str_replace([' ', "\xc2\xa0", 'MAD', 'mad', 'DH', 'dh', '€', '$'], '', $str);
        $str = str_replace(',', '.', $str);

        // Extraire la première valeur décimale
        if (preg_match('/(\d+(?:\.\d+)?)/', $str, $matches)) {
            return round((float)$matches[1], 2);
        }

        return null;
    }

    /**
     * Parser une valeur booléenne (Oui, Non, 1, 0, Vrai, Faux).
     */
    protected function parseBoolean(mixed $val, bool $default = true): bool
    {
        if ($val === null || $val === '') {
            return $default;
        }

        $norm = $this->normalizeString((string)$val);
        if (in_array($norm, ['1', 'oui', 'yes', 'vrai', 'true', 'ok', 'dispo', 'enstock'])) {
            return true;
        }
        if (in_array($norm, ['0', 'non', 'no', 'faux', 'false', 'epuise', 'rupture'])) {
            return false;
        }

        return $default;
    }

    /**
     * Vérifier si toute la ligne est vide.
     */
    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $cell) {
            if ($cell !== null && trim((string)$cell) !== '') {
                return false;
            }
        }
        return true;
    }

    /**
     * Normaliser une chaîne pour les comparaisons insensibles aux accents et à la casse.
     */
    protected function normalizeString(string $str): string
    {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        // Remplacement des accents standards
        $transliterator = @transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $str);
        if ($transliterator !== false) {
            $str = $transliterator;
        } else {
            $str = strtr(utf8_decode($str), 
                utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 
                'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        }
        return preg_replace('/[^a-z0-9]/', '', $str);
    }

    /**
     * Nettoyer les données de ligne pour enregistrement dans le rapport JSON.
     */
    protected function sanitizeRowForLog(array $row): array
    {
        $cleaned = [];
        foreach ($row as $k => $v) {
            if ($v !== null && $v !== '') {
                $cleaned[$k] = mb_substr(trim((string)$v), 0, 100);
            }
        }
        return $cleaned;
    }
}
