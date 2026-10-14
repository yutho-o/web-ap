# Environnement Docker

Cet environnement fournit un serveur web PHP, une base de donnees MySQL
et phpMyAdmin, sans rien installer d'autre sur le poste.

## Organisation

    docker-compose.yml   Definition des services (a la racine du projet)
    docker/web/           Image du serveur web
      Dockerfile           Extensions PHP installees, configuration
      conf.d/erreurs.ini   Reglages PHP (erreurs affichees, taille des envois)
      conf.d/xdebug.ini    Reglages du debogueur

## Demarrer

Depuis la racine du projet :

    docker compose up -d

Le premier demarrage est long : les images sont telechargees et
construites. Les suivants prennent quelques secondes.

## Ajouter une extension PHP

Ajouter son nom a la ligne `docker-php-ext-install` de
`docker/web/Dockerfile`, puis reconstruire l'image :

    docker compose up -d --build

## Adresses

| Usage | Adresse |
|---|---|
| Pages du dossier `cours/php` | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| Verification de Xdebug | http://localhost:8080/verif/config-xdebug.php |

## Debogueur

L'image installe Xdebug, qui permet d'arreter une page en cours
d'execution depuis VS Code, et rend cliquables les noms de fichiers sur
les pages d'erreur PHP. La mise en route est decrite dans
`cours/verif/README.md`.

Si l'environnement a ete construit avant l'ajout de Xdebug, reconstruire
l'image :

    docker compose up -d --build

Les liens cliquables des pages d'erreur ont besoin de savoir ou se
trouve le dossier `cours` sur le poste (variable `HOST_COURS_DIR`),
deduite du dossier depuis lequel `docker compose` est lance : toujours
demarrer depuis la racine du projet, comme indique ci-dessus.

## Identifiants de la base

| Element | Valeur |
|---|---|
| Serveur | `db` |
| Base | `esport` |
| Utilisateur | `etudiant` |
| Mot de passe | `etudiant` |

Le nom du serveur est `db`, et non `localhost` : depuis le conteneur
web, la base est une machine voisine designee par son nom de service
(celui du service `db` dans docker-compose.yml).

## Arreter

    docker compose down

Les donnees de la base sont conservees. Pour tout effacer et repartir
d'une base vierge :

    docker compose down -v
