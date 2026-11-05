# Bugfix Requirements Document

## Introduction

Dans le formulaire de création et de modification d'une pièce (véhicule), le champ de sélection du véhicule est actuellement optionnel : l'utilisateur peut soumettre le formulaire sans choisir un véhicule, ce qui place la pièce dans un état « à classer » (`vehicule_id = null`). Cette option « à classer » doit être entièrement supprimée : la classification du véhicule doit devenir obligatoire, et toutes les références à ce statut dans l'interface et la base de données doivent être éliminées.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN l'utilisateur soumet le formulaire de création d'une pièce sans sélectionner de véhicule THEN le système accepte la soumission et crée la pièce avec `vehicule_id = null` (statut « à classer »)

1.2 WHEN l'utilisateur soumet le formulaire de modification d'une pièce sans sélectionner de véhicule THEN le système accepte la soumission et enregistre la pièce avec `vehicule_id = null` (statut « à classer »)

1.3 WHEN le formulaire de création ou de modification d'une pièce est affiché THEN le système propose une option vide « aucun véhicule (statut à classer) » comme valeur sélectionnable dans le champ véhicule

1.4 WHEN des pièces sont importées via CSV et qu'aucun véhicule n'est reconnu THEN le système crée ces pièces avec `vehicule_id = null`, les ajoutant silencieusement au stock « à classer »

1.5 WHEN des pièces avec `vehicule_id = null` existent en base de données THEN le système les affiche dans l'interface d'administration avec le badge « ⚠️ À classer » et un compteur d'alerte

### Expected Behavior (Correct)

2.1 WHEN l'utilisateur soumet le formulaire de création d'une pièce sans sélectionner de véhicule THEN le système SHALL rejeter la soumission avec une erreur de validation indiquant que le véhicule est obligatoire

2.2 WHEN l'utilisateur soumet le formulaire de modification d'une pièce sans sélectionner de véhicule THEN le système SHALL rejeter la soumission avec une erreur de validation indiquant que le véhicule est obligatoire

2.3 WHEN le formulaire de création ou de modification d'une pièce est affiché THEN le système SHALL afficher le champ véhicule comme requis (marqué `*`), sans option vide ni mention « à classer »

2.4 WHEN la colonne `vehicule_id` de la table `pieces` est définie THEN le système SHALL la contraindre comme `NOT NULL` au niveau de la base de données

2.5 WHEN des pièces existantes avec `vehicule_id = null` sont présentes en base de données THEN le système SHALL les supprimer ou les migrer vers un véhicule valide avant d'appliquer la contrainte `NOT NULL`

### Unchanged Behavior (Regression Prevention)

3.1 WHEN l'utilisateur soumet le formulaire de création ou de modification d'une pièce avec un véhicule valide sélectionné THEN le système SHALL CONTINUE TO créer ou mettre à jour la pièce correctement avec le `vehicule_id` fourni

3.2 WHEN l'utilisateur filtre la liste des pièces par marque ou par véhicule THEN le système SHALL CONTINUE TO retourner les pièces correspondant aux critères de filtrage

3.3 WHEN l'utilisateur consulte les détails d'une pièce sur le catalogue public THEN le système SHALL CONTINUE TO afficher le nom du véhicule associé

3.4 WHEN l'utilisateur exporte le catalogue en CSV THEN le système SHALL CONTINUE TO inclure le nom du véhicule associé à chaque pièce dans l'export

3.5 WHEN l'administrateur consulte le tableau de bord THEN le système SHALL CONTINUE TO afficher les statistiques de pièces (total, actives, position RL) sans le compteur « à classer »
