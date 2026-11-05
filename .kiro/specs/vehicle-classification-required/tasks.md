# Implementation Plan

## Overview

Ce plan d'implémentation suit la méthodologie de condition de bug :
1. **Explorer** — écrire des tests *avant* le correctif pour confirmer le bug (Bug Condition)
2. **Préserver** — écrire des tests pour le comportement non-bugué (Preservation Requirements)
3. **Implémenter** — appliquer le correctif en connaissance de cause
4. **Valider** — vérifier que le correctif fonctionne sans régressions

## Tasks

- [ ] 1. Écrire le test d'exploration de la condition de bug (avant le correctif)
  - **Property 1: Bug Condition** - Soumission sans véhicule acceptée silencieusement
  - **CRITICAL** : Ce test DOIT ÉCHOUER sur le code non corrigé — l'échec confirme que le bug existe
  - **NE PAS tenter de corriger le test ou le code quand il échoue**
  - **NOTE** : Ce test encode le comportement attendu — il validera le correctif quand il passera après l'implémentation
  - **GOAL** : Faire émerger des contre-exemples prouvant l'existence du bug
  - **Approche PBT scopée** : Pour chaque entrée satisfaisant `isBugCondition`, le système ne doit PAS accepter la soumission
  - Créer le test avec `php artisan make:test --phpunit VehicleRequiredBugConditionTest`
  - **Test 1 — `store()` sans `vehicule_id`** : POST vers `admin.pieces.store` avec un payload valide mais sans `vehicule_id` → sur le code non corrigé, la pièce est créée (HTTP 302 vers index) et `Piece::whereNull('vehicule_id')->exists()` est vrai. Le test asserte que la réponse contient une erreur de validation sur `vehicule_id` et qu'aucune pièce n'est créée — ce test **ÉCHOUE** sur le code non corrigé (confirme le bug)
  - **Test 2 — `update()` avec `vehicule_id = null`** : PUT vers `admin.pieces.update` en retirant le `vehicule_id` d'une pièce existante → sur le code non corrigé, la pièce est mise à jour avec `vehicule_id = null`. Le test asserte une erreur de validation et aucune modification — ce test **ÉCHOUE** sur le code non corrigé
  - **Test 3 — Import CSV sans véhicule reconnu** : Appeler `CatalogImportService::import()` avec un CSV dont la colonne modèle est absente ou inconnue → sur le code non corrigé, une pièce est créée avec `vehicule_id = null`. Le test asserte qu'aucune pièce n'est créée et qu'une erreur figure dans le rapport — ce test **ÉCHOUE** sur le code non corrigé
  - **EXPECTED OUTCOME** : Les tests ÉCHOUENT (c'est correct — cela prouve l'existence du bug)
  - Documenter les contre-exemples trouvés (ex. : « POST /admin/pieces sans vehicule_id → 302 redirect, pièce créée avec vehicule_id=null »)
  - Marquer la tâche terminée quand les tests sont écrits, exécutés et l'échec documenté
  - _Requirements: 1.1, 1.2, 1.4_

- [ ] 2. Écrire les tests de préservation (avant le correctif)
  - **Property 2: Preservation** - Comportement normal avec véhicule valide
  - **IMPORTANT** : Suivre la méthodologie d'observation en premier
  - Créer le test avec `php artisan make:test --phpunit VehicleRequiredPreservationTest`
  - **Observation sur le code non corrigé** :
    - Observer que `store()` avec un `vehicule_id` valide crée la pièce (HTTP 302 succès)
    - Observer que `update()` avec un `vehicule_id` valide met à jour la pièce correctement
    - Observer que l'index avec filtre `marque_id` ou `vehicule_id` retourne les bonnes pièces
  - **Test PBT 1 — Création avec vehicule_id valide** : Générer plusieurs payloads valides (différentes positions, familles, années) avec un `vehicule_id` existant aléatoire → asserter que `store()` crée toujours la pièce avec le bon `vehicule_id` et retourne HTTP 302 vers index. Vérifier avec `Piece::where('vehicule_id', $vehiculeId)->exists()` (de `Preservation Requirements` : création/modification avec vehicule valide doit continuer à fonctionner)
  - **Test PBT 2 — Modification avec vehicule_id valide** : Générer des modifications avec différents `vehicule_id` valides → asserter que `update()` persiste correctement le changement
  - **Test 3 — Filtrage liste par marque/véhicule** : Créer des pièces avec véhicules connus, filtrer par `marque_id` et `vehicule_id` → asserter que les résultats correspondent aux critères (Requirements 3.2)
  - **Test 4 — Import CSV avec véhicule reconnu** : Importer un CSV dont la colonne modèle correspond à un véhicule existant → asserter que la pièce est créée avec le bon `vehicule_id` et sans erreur dans le rapport
  - Exécuter les tests sur le code NON CORRIGÉ
  - **EXPECTED OUTCOME** : Les tests PASSENT (confirme le comportement de référence à préserver)
  - Marquer la tâche terminée quand les tests sont écrits, exécutés et passent sur le code non corrigé
  - _Requirements: 3.1, 3.2, 3.4_

- [ ] 3. Correctif — Supprimer le concept « À classer » et rendre le véhicule obligatoire

  - [ ] 3.1 Créer la migration pour contraindre `vehicule_id` à NOT NULL
    - Créer avec `php artisan make:migration make_pieces_vehicule_id_not_nullable --no-interaction`
    - Dans `up()` : supprimer d'abord les pièces orphelines avec `DB::table('pieces')->whereNull('vehicule_id')->delete()`
    - Modifier la colonne : `$table->foreignId('vehicule_id')->nullable(false)->change()` et retirer `nullOnDelete`
    - Dans `down()` : rétablir `->nullable()` pour réversibilité
    - Exécuter avec `php artisan migrate`
    - _Bug_Condition: isBugCondition(input) où input.vehicule_id IS NULL — la contrainte DB empêche la persistance même si la validation était contournée_
    - _Expected_Behavior: La colonne vehicule_id ne peut plus contenir NULL en base de données_
    - _Preservation: Les pièces avec vehicule_id valide ne sont pas affectées_
    - _Requirements: 2.4, 2.5_

  - [ ] 3.2 Corriger les règles de validation dans `PieceController`
    - Dans `store()` et `update()` : remplacer `'vehicule_id' => 'nullable|exists:vehicules,id'` par `'vehicule_id' => 'required|exists:vehicules,id'`
    - Dans `store()` : remplacer `'vehicule_id' => $validated['vehicule_id'] ?? null` par `'vehicule_id' => $validated['vehicule_id']`
    - Dans `update()` : remplacer `$piece->vehicule_id = $validated['vehicule_id'] ?? null` par `$piece->vehicule_id = $validated['vehicule_id']`
    - Supprimer le bloc `if ($request->filled('a_classer'))` dans `index()` et retirer `$aClasserCount` du compact
    - _Bug_Condition: isBugCondition(request) où request.vehicule_id IS NULL OR request.vehicule_id = ''_
    - _Expected_Behavior: La validation Laravel rejette la soumission avec erreur sur 'vehicule_id', aucune pièce créée/modifiée_
    - _Preservation: Les soumissions avec vehicule_id valide continuent à fonctionner exactement comme avant_
    - _Requirements: 2.1, 2.2_

  - [ ] 3.3 Corriger `CatalogImportService` pour rejeter les lignes sans véhicule
    - Après l'appel à `resolveVehicule()`, ajouter une vérification : si `$vehicule === null`, ajouter une entrée dans `$errors` (motif : « Véhicule introuvable : aucun modèle reconnu. La pièce ne peut pas être importée sans véhicule associé. ») et `continue` vers la ligne suivante
    - Supprimer l'utilisation de `$vehicule?->id` (opérateur null-safe) dans `Piece::create()` et dans la recherche de doublon — remplacer par `$vehicule->id` (non-nullable après la vérification)
    - Mettre à jour le commentaire de `resolveVehicule()` : retirer la mention « À classer »
    - _Bug_Condition: isBugCondition(csvRow) où resolveVehicule(csvRow) RETURNS null_
    - _Expected_Behavior: La ligne est rejetée avec motif explicite dans le rapport d'import, aucune pièce créée avec vehicule_id=null_
    - _Preservation: Les lignes avec véhicule reconnu continuent à être créées/mises à jour correctement_
    - _Requirements: 1.4, 2.1_

  - [ ] 3.4 Mettre à jour les vues Blade des formulaires (`create.blade.php` et `edit.blade.php`)
    - Dans les deux fichiers : ajouter `<span class="text-brand-red">*</span>` au label du champ véhicule
    - Ajouter `data-required="true"` au conteneur `.searchable-select` du véhicule (cohérent avec le traitement existant du champ famille)
    - Dans `edit.blade.php` : supprimer le bloc `@if ($piece->isAClasser())` affichant la bannière « ⚠️ Cette pièce est actuellement À classer »
    - _Requirements: 2.3_

  - [ ] 3.5 Nettoyer `index.blade.php` — supprimer toutes les références « À classer »
    - Supprimer la bannière d'alerte `@if ($aClasserCount > 0 ...)`
    - Supprimer le `<select name="a_classer">` et son option `⚠️ À classer (sans véhicule)`
    - Supprimer la classe conditionnelle `{{ $piece->isAClasser() ? 'bg-amber-50/30' : '' }}` sur les lignes du tableau
    - Supprimer le badge `⚠️ À classer` dans la colonne véhicule (le bloc `@else` du `@if ($piece->vehicule)`)
    - _Requirements: 1.3, 1.5_

  - [ ] 3.6 Nettoyer `DashboardController` — supprimer le compteur « À classer »
    - Retirer `'pieces_a_classer_count' => Piece::aClasser()->count()` du tableau `$stats`
    - Supprimer le widget correspondant dans la vue `admin/dashboard.blade.php` si présent
    - _Requirements: 3.5_

  - [ ] 3.7 Nettoyer le modèle `Piece` — supprimer les scopes et méthodes résiduels
    - Vérifier l'absence de toute autre référence : `grep -rn "aClasser\|isAClasser\|a_classer" app/ resources/`
    - Supprimer `scopeAClasser()`, `scopeClasse()`, et `isAClasser()` du modèle `Piece`
    - _Requirements: 1.5_

  - [ ] 3.8 Formater le code PHP modifié avec Pint
    - Exécuter `vendor/bin/pint --dirty --format agent` sur tous les fichiers PHP modifiés
    - _Requirements: 2.1, 2.2_

  - [ ] 3.9 Vérifier que le test d'exploration de la condition de bug passe désormais
    - **Property 1: Expected Behavior** - Rejet de soumission sans véhicule
    - **IMPORTANT** : Ré-exécuter le MÊME test de la tâche 1 — NE PAS écrire un nouveau test
    - Le test de la tâche 1 encode le comportement attendu : quand il passe, il confirme que le correctif est satisfait
    - Exécuter : `php artisan test --compact --filter VehicleRequiredBugConditionTest`
    - **EXPECTED OUTCOME** : Les tests PASSENT (confirme que le bug est corrigé)
    - Si un test échoue encore, revoir les étapes 3.1 à 3.3 correspondantes
    - _Requirements: 2.1, 2.2, 2.4_

  - [ ] 3.10 Vérifier que les tests de préservation passent toujours
    - **Property 2: Preservation** - Comportement normal avec véhicule valide inchangé
    - **IMPORTANT** : Ré-exécuter les MÊMES tests de la tâche 2 — NE PAS écrire de nouveaux tests
    - Exécuter : `php artisan test --compact --filter VehicleRequiredPreservationTest`
    - **EXPECTED OUTCOME** : Les tests PASSENT (confirme l'absence de régression)
    - Si un test échoue, identifier quelle étape du correctif a cassé le comportement et corriger
    - _Requirements: 3.1, 3.2, 3.4_

- [ ] 4. Checkpoint — S'assurer que tous les tests passent
  - Exécuter la suite complète : `php artisan test --compact --filter "VehicleRequired"`
  - Vérifier que les deux suites (BugCondition + Preservation) sont vertes
  - Vérifier visuellement dans le navigateur : formulaire de création sans véhicule → message d'erreur visible ; formulaire avec véhicule → création réussie ; index sans bannière « À classer » ; tableau de bord sans compteur orphelin
  - Si des questions surgissent (ex. : pièces existantes avec `vehicule_id = null` à migrer vers un véhicule spécifique plutôt qu'à supprimer), demander à l'utilisateur avant d'exécuter la migration

## Task Dependency Graph

```json
{
  "waves": [
    { "wave": 1, "tasks": ["1", "2"] },
    { "wave": 2, "tasks": ["3.1", "3.2", "3.3", "3.4", "3.5", "3.6", "3.7", "3.8"] },
    { "wave": 3, "tasks": ["3.9", "3.10"] },
    { "wave": 4, "tasks": ["4"] }
  ]
}
```

- Tâches 1 et 2 sont indépendantes et peuvent être réalisées en parallèle (vague 1)
- Les sous-tâches 3.1 à 3.8 dépendent de 1 et 2, peuvent être réalisées dans l'ordre indiqué — migration (3.1) en premier (vague 2)
- Les sous-tâches 3.9 et 3.10 dépendent de l'ensemble des sous-tâches 3.1 à 3.8 (vague 3)
- La tâche 4 dépend de la complétion totale de la tâche 3 (vague 4)

## Notes

- **Ordre critique** : Ne jamais implémenter le correctif avant d'avoir écrit et exécuté les tests des tâches 1 et 2
- **Migration destructive** : La suppression des pièces avec `vehicule_id = null` (tâche 3.1) est irréversible en production — confirmer avec l'utilisateur s'il préfère une migration vers un véhicule par défaut
- **PBT dans Laravel** : Utiliser des boucles de génération de données via les factories Eloquent pour simuler des tests property-based (ex. : `Piece::factory()->count(20)->create([...])` avec `vehicule_id` aléatoire parmi `Vehicule::inRandomOrder()->first()->id`)
- **Nettoyage du modèle** (tâche 3.7) : Faire en dernier, après avoir vérifié avec grep qu'aucun code ne référence encore les scopes supprimés
