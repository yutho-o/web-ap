# Activités professionnelles — BTS SIO 1re année

Dépôt de travail personnel pour le semestre 1 : HTML, CSS, JavaScript,
PHP et base de données.

## Organisation

```
cours/
  html-css/       Pages HTML et feuilles de style
  javascript/     Scripts exécutés par le navigateur
  php/            Pages exécutées par le serveur
  verif/          Pages de test de l’installation et du débogueur
  projet/         Projet fil rouge
  bac-a-sable/    Essais libres
ressources/       Fichiers fournis (base de données, images)
docker/           Environnement Docker (voir docker-compose.yml)
```

## Premiers pas

1. Ouvrir ce dossier dans **VS Code** (« Fichier » puis « Ouvrir le dossier »).
2. Installer les extensions proposées à l’ouverture.
3. Vérifier l’installation en suivant **`ressources/VERIFICATION.md`**.

## Sauvegarder son travail

Sauvegarder régulièrement, sans attendre la fin de la séance :

```bash
git add .
git commit -m "Message donné dans le cours"
git push
```

En cas de doute sur ce qui va être envoyé :

```bash
git status
```

## Base de données

Le fichier **`ressources/base-de-donnees/esport.sql`** s’importe depuis
**phpMyAdmin**, onglet « Importer ». Il peut être réimporté à tout moment :
la base est alors recréée de zéro, ce qui efface toutes les modifications
faites depuis le dernier import.

## En cas de blocage

Une page qui ne s’affiche pas comme prévu n’est pas une faute : c’est la
situation ordinaire du développement. Vérifier dans l’ordre :

- l’adresse utilisée ;
- le nom exact des fichiers ;
- la console du navigateur (**F12**).
