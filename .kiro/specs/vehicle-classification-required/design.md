# Vehicle Classification Required — Bugfix Design

## Overview

Le bug permet à l'utilisateur de créer ou modifier une pièce sans sélectionner de véhicule,
laissant la pièce dans un état intermédiaire (`vehicule_id = null`) appelé « À classer ».
Ce concept doit être entièrement supprimé : la colonne `vehicule_id` doit devenir `NOT NULL`,
la validation serveur doit rejeter toute soumission sans véhicule, l'import CSV doit rejeter
les lignes sans véhicule identifié, et toutes les références UI à ce statut doivent être
retirées.

Le correctif couvre six couches du projet : migration de base de données, validation du
contrôleur (`PieceController`), service d'import (`CatalogImportService`), vues Blade des
formulaires, vue index, et contrôleur du tableau de bord (`DashboardController`).

---

## Glossary

- **Bug_Condition (C)** : Condition déclenchant le bug — soumission d'une pièce avec
  `vehicule_id` absent/nul, ou création via import CSV sans véhicule reconnu.
- **Propriété (P)** : Comportement attendu lorsque C est vraie — le système doit rejeter la
  soumission avec une erreur explicite plutôt qu'accepter une valeur nulle.
- **Préservation** : Comportements existants qui ne doivent pas changer — création/modification
  avec un véhicule valide, filtrage, export CSV, catalogue public.
- **`PieceController`** : Contrôleur dans `app/Http/Controllers/Admin/PieceController.php`
  gérant les actions CRUD sur les pièces ; contient les règles de validation `store()` et
  `update()`.
- **`CatalogImportService`** : Service dans `app/Services/CatalogImportService.php` traitant
  l'import CSV/XLSX ; résout le véhicule via `resolveVehicule()` et peut retourner `null`.
- **`scopeAClasser`** : Scope Eloquent sur `Piece` filtrant `whereNull('vehicule_id')` ;
  utilisé dans `DashboardController` et `PieceController::index()`.
- **`vehicule_id`** : Clé étrangère vers la table `vehicules` ; actuellement `nullable()` dans
  la migration, doit devenir `NOT NULL`.

---

## Bug Details

### Bug Condition

Le bug se manifeste lorsqu'une pièce est créée ou modifiée sans `vehicule_id`, que ce soit
par soumission de formulaire ou par import CSV. `PieceController::store()` et `update()`
déclarent `vehicule_id` comme `'nullable|exists:vehicules,id'`, ce qui autorise la valeur
nulle. `CatalogImportService::resolveVehicule()` retourne `null` silencieusement quand aucun
véhicule n'est trouvé.

**Spécification formelle :**

```
FUNCTION isBugCondition(input)
  INPUT: input de type FormRequest | CsvRow
  OUTPUT: boolean

  RETURN input.vehicule_id IS NULL
         OR input.vehicule_id = ''
         OR (input IS CsvRow AND resolveVehicule(input) RETURNS null)
END FUNCTION
```

### Exemples

- **Formulaire création, aucun véhicule sélectionné** : L'utilisateur soumet le formulaire
  sans choisir de véhicule → la pièce est créée avec `vehicule_id = null`. *(Comportement
  défectueux — attendu : erreur de validation)*
- **Formulaire modification, véhicule effacé** : L'utilisateur édite une pièce et retire le
  véhicule → la pièce est enregistrée avec `vehicule_id = null`. *(Comportement défectueux —
  attendu : erreur de validation)*
- **Import CSV, colonne véhicule vide** : Une ligne CSV sans colonne modèle, ou dont le modèle
  est inconnu → la pièce est créée avec `vehicule_id = null`. *(Comportement défectueux —
  attendu : la ligne est rejetée avec un motif explicite)*
- **Import CSV, véhicule connu** : Une ligne CSV avec un modèle reconnu → la pièce est créée
  correctement avec un `vehicule_id` valide. *(Cas normal, non affecté)*

---

## Expected Behavior

### Preservation Requirements

**Comportements non modifiés :**
- La création et la modification d'une pièce avec un `vehicule_id` valide doivent continuer à
  fonctionner exactement comme avant.
- Le filtrage de la liste des pièces par marque ou par véhicule doit retourner les mêmes
  résultats.
- L'affichage des détails d'une pièce sur le catalogue public doit continuer à présenter le
  nom du véhicule associé.
- L'export CSV du catalogue doit continuer à inclure le nom du véhicule pour chaque pièce.
- Le tableau de bord doit continuer à afficher les statistiques de pièces (total, actives,
  position RL) ; le compteur « À classer » est supprimé (sa valeur sera toujours 0 après le
  correctif).

**Périmètre :**
Tout ce qui ne touche pas à la présence/absence de `vehicule_id` dans une soumission ou une
ligne d'import est entièrement hors périmètre du correctif.

---

## Hypothesized Root Cause

Sur la base de l'analyse du code, les causes directes sont :

1. **Règle de validation `nullable` dans `PieceController`** : Les méthodes `store()` et
   `update()` définissent `'vehicule_id' => 'nullable|exists:vehicules,id'`. Remplacer
   `nullable` par `required` est le changement central côté validation.

2. **Retour `null` silencieux dans `CatalogImportService::resolveVehicule()`** : La méthode
   retourne `null` sans erreur quand aucun véhicule n'est trouvé. Ce `null` est ensuite
   passé directement à `Piece::create(['vehicule_id' => $vehicule?->id, ...])`.

3. **Contrainte `nullable()` au niveau de la migration** : La colonne est définie comme
   `$table->foreignId('vehicule_id')->nullable()->constrained('vehicules')->nullOnDelete()`.
   Sans contrainte `NOT NULL`, la base de données accepte silencieusement les valeurs nulles.

4. **UI du formulaire sans marqueur de champ obligatoire** : Le champ véhicule dans
   `create.blade.php` et `edit.blade.php` n'a pas d'astérisque `*` ni d'attribut HTML
   `required` sur le champ caché, laissant l'utilisateur penser que ce champ est facultatif.

5. **Références résiduelles au statut « À classer »** : Le filtre `a_classer` dans
   `PieceController::index()`, le scope `scopeAClasser`, la méthode `isAClasser()` sur le
   modèle, le compteur dans `DashboardController`, la bannière d'alerte et le filtre dans
   `index.blade.php`, et le message dans `edit.blade.php` propagent ce concept dans
   l'ensemble de l'application.

---

## Correctness Properties

Property 1: Bug Condition — Rejet de soumission sans véhicule

_For any_ soumission de formulaire (création ou modification de pièce) où `isBugCondition`
est vraie (vehicule_id absent ou nul), la fonction corrigée SHALL retourner une réponse de
redirection avec des erreurs de validation indiquant que le véhicule est obligatoire, et
aucune pièce ne doit être créée ou modifiée en base de données.

**Validates: Requirements 2.1, 2.2**

Property 2: Preservation — Succès de soumission avec véhicule valide

_For any_ soumission de formulaire où `isBugCondition` est fausse (vehicule_id présent et
existant en base), la fonction corrigée SHALL produire le même résultat que la fonction
originale — création ou mise à jour réussie de la pièce avec le `vehicule_id` fourni.

**Validates: Requirements 3.1**

---

## Fix Implementation

### Changes Required

#### 1. Nouvelle migration — Contrainte NOT NULL sur `vehicule_id`

**Fichier :** `database/migrations/YYYY_MM_DD_HHMMSS_make_pieces_vehicule_id_not_nullable.php`

**Changements spécifiques :**
- Avant d'ajouter la contrainte `NOT NULL`, supprimer ou migrer toutes les pièces existantes
  avec `vehicule_id = null` (via `DB::table('pieces')->whereNull('vehicule_id')->delete()`
  dans la méthode `up()`).
- Modifier la colonne : `$table->foreignId('vehicule_id')->nullable(false)->change()` et
  supprimer la clause `nullOnDelete`.
- La méthode `down()` doit rétablir `nullable()` pour réversibilité.

#### 2. `PieceController` — Règles de validation

**Fichier :** `app/Http/Controllers/Admin/PieceController.php`

**Changements spécifiques :**
- Dans `store()` et `update()` : remplacer `'vehicule_id' => 'nullable|exists:vehicules,id'`
  par `'vehicule_id' => 'required|exists:vehicules,id'`.
- Dans `store()` : remplacer `'vehicule_id' => $validated['vehicule_id'] ?? null` par
  `'vehicule_id' => $validated['vehicule_id']`.
- Dans `update()` : remplacer `$piece->vehicule_id = $validated['vehicule_id'] ?? null` par
  `$piece->vehicule_id = $validated['vehicule_id']`.
- Supprimer le filtre `a_classer` de `index()` et retirer `$aClasserCount` de la vue compacte.

#### 3. `CatalogImportService` — Rejet des lignes sans véhicule

**Fichier :** `app/Services/CatalogImportService.php`

**Changements spécifiques :**
- Après l'appel à `resolveVehicule()`, vérifier si `$vehicule === null` et, si c'est le cas,
  ajouter une entrée dans `$errors` avec un motif explicite (ex. : « Véhicule introuvable :
  aucun modèle reconnu. La pièce ne peut pas être créée sans véhicule associé. ») et
  `continue` vers la ligne suivante.
- Retirer la logique de déduplication basée sur `vehicule_id = null` dans la requête de
  recherche de doublon (la clé d'unicité reste désignation + vehicule_id + position + année,
  mais un `vehicule_id` nul ne peut plus être stocké).

#### 4. Vues Blade — Formulaires `create.blade.php` et `edit.blade.php`

**Fichiers :**
- `resources/views/admin/pieces/create.blade.php`
- `resources/views/admin/pieces/edit.blade.php`

**Changements spécifiques :**
- Ajouter `<span class="text-brand-red">*</span>` au label du champ véhicule dans les deux
  formulaires.
- Ajouter `data-required="true"` au conteneur `.searchable-select` du véhicule (comme c'est
  déjà fait pour la famille), afin que le JS empêche la soumission sans valeur.
- Dans `edit.blade.php` : supprimer le bloc `@if ($piece->isAClasser())` qui affiche la
  bannière « ⚠️ Cette pièce est actuellement À classer ».

#### 5. Vue `index.blade.php` — Suppression des références « À classer »

**Fichier :** `resources/views/admin/pieces/index.blade.php`

**Changements spécifiques :**
- Supprimer la bannière d'alerte `@if ($aClasserCount > 0 ...)`.
- Supprimer l'option `<option value="1">⚠️ À classer (sans véhicule)</option>` du filtre
  `a_classer` et le `<select name="a_classer">` entier (ou simplifier le filtre de statut).
- Supprimer la classe conditionnelle `{{ $piece->isAClasser() ? 'bg-amber-50/30' : '' }}` sur
  la ligne du tableau.
- Supprimer le badge `⚠️ À classer` dans la colonne véhicule (le `@else` du bloc
  `@if ($piece->vehicule)`).

#### 6. `DashboardController` — Suppression du compteur

**Fichier :** `app/Http/Controllers/Admin/DashboardController.php`

**Changements spécifiques :**
- Retirer `'pieces_a_classer_count' => Piece::aClasser()->count()` du tableau `$stats`.
- Si la vue du tableau de bord affiche ce compteur, supprimer le widget correspondant.

#### 7. Modèle `Piece` — Nettoyage optionnel des scopes résiduels

**Fichier :** `app/Models/Piece.php`

**Changements spécifiques :**
- Supprimer ou commenter `scopeAClasser()`, `scopeClasse()`, et `isAClasser()` une fois
  qu'aucun autre code ne les référence. (À faire en dernier, après avoir vérifié l'absence de
  références.)

---

## Testing Strategy

### Validation Approach

La stratégie suit deux phases : (1) observer le comportement défectueux sur le code non
corrigé pour confirmer l'hypothèse, puis (2) vérifier que le correctif satisfait les
propriétés attendues sans régressions.

### Exploratory Bug Condition Checking

**But :** Confirmer que le code actuel accepte des soumissions sans véhicule.

**Plan :** Écrire des tests qui soumettent le formulaire sans `vehicule_id` et assertent que
la réponse est un succès (ce qui prouve le bug sur le code non corrigé).

**Test Cases :**
1. **POST store() sans vehicule_id** : Soumettre un formulaire valide sans `vehicule_id` →
   sur le code non corrigé, la pièce est créée ; on s'attend à ce test échoue après
   correction. *(échoue sur le code non corrigé = confirme le bug)*
2. **PUT update() vehicule_id retiré** : Soumettre une modification avec `vehicule_id = null`
   → sur le code non corrigé, la pièce est mise à jour. *(confirme le bug)*
3. **Import CSV ligne sans véhicule** : Importer une ligne CSV sans colonne modèle reconnue
   → sur le code non corrigé, une pièce est créée avec `vehicule_id = null`. *(confirme le
   bug)*

**Contre-exemples attendus :**
- La réponse HTTP est un 302 de succès (ou 200) au lieu d'une erreur de validation 422/302.
- Une pièce avec `vehicule_id = null` existe en base après la soumission.

### Fix Checking

**But :** Vérifier que, pour tout input où `isBugCondition` est vraie, la version corrigée
rejette la soumission.

**Pseudocode :**
```
FOR ALL request WHERE isBugCondition(request) DO
  response := PieceController_fixed.store(request)
              OR PieceController_fixed.update(request)
  ASSERT response.hasErrors('vehicule_id')
  ASSERT Piece.count() UNCHANGED
END FOR
```

### Preservation Checking

**But :** Vérifier que, pour tout input où `isBugCondition` est fausse (vehicule_id fourni
et valide), le comportement reste identique.

**Pseudocode :**
```
FOR ALL request WHERE NOT isBugCondition(request) DO
  ASSERT PieceController_original.store(request)
       = PieceController_fixed.store(request)
END FOR
```

**Approche :** Les tests property-based sont recommandés pour la preservation checking car ils
génèrent automatiquement de nombreuses combinaisons de données de pièce valides, vérifiant
ainsi que le changement de validation n'affecte pas les chemins de succès.

**Test Cases :**
1. **Préservation création valide** : Observer que `store()` avec un `vehicule_id` valide
   crée la pièce correctement → écrire un test qui vérifie ce comportement persiste après
   correction.
2. **Préservation modification valide** : Observer que `update()` avec un `vehicule_id` valide
   met à jour correctement → idem.
3. **Préservation filtres liste** : Vérifier que les filtres marque/véhicule de l'index
   retournent les mêmes résultats.

### Unit Tests

- Tester que `store()` avec `vehicule_id = null` retourne des erreurs de validation.
- Tester que `store()` avec un `vehicule_id` valide crée la pièce.
- Tester que `update()` avec `vehicule_id = null` retourne des erreurs de validation.
- Tester que `CatalogImportService::import()` rejette les lignes sans véhicule reconnu avec
  un motif d'erreur.
- Tester que `CatalogImportService::import()` crée correctement les pièces dont le véhicule
  est reconnu.

### Property-Based Tests

- Générer des payloads de création aléatoires avec `vehicule_id` toujours nul → asserter que
  `store()` retourne toujours une erreur de validation (Property 1).
- Générer des payloads de création aléatoires avec un `vehicule_id` valide aléatoirement
  sélectionné → asserter que `store()` crée toujours la pièce avec succès (Property 2).
- Générer des fichiers CSV avec des lignes mixes (véhicule reconnu / non reconnu) → asserter
  que seules les lignes avec véhicule reconnu sont créées.

### Integration Tests

- Tester le flux complet : affichage du formulaire de création → soumission sans véhicule →
  retour sur le formulaire avec message d'erreur visible.
- Tester le flux d'import : upload d'un CSV mixte → rapport d'erreurs listant les lignes
  sans véhicule, aucune pièce orpheline en base.
- Tester l'index après correction : aucune bannière « À classer », filtre `a_classer` absent.
- Tester le tableau de bord : `pieces_a_classer_count` absent des stats affichées.
