# Utiliser le débogueur (Xdebug)

Un débogueur permet d’arrêter un programme en plein milieu de son
exécution pour regarder ce que contiennent les variables à cet instant.
C’est l’outil qui remplace les `echo` semés un peu partout dans le code.

Prérequis : avoir suivi `ressources/VERIFICATION.md` jusqu’au bout. Ce
document-ci explique comment se servir du débogueur, pas comment vérifier
qu’il est installé.

Ce dossier contient aussi `verification.html` (et ses fichiers
`.css`/`.js`) et `verification.php`, sans rapport avec le débogueur : ce
sont les tests de l’éditeur, du navigateur et du serveur PHP, couverts
par `ressources/VERIFICATION.md`.

Les deux pages qui concernent le débogueur :

| Fichier             | Rôle                                                           |
| ------------------- | -------------------------------------------------------------- |
| `config-xdebug.php` | Diagnostic du serveur, à rouvrir à tout moment en cas de doute |
| `test-debogage.php` | Terrain d’essai pour poser un point d’arrêt                    |

## 1. Vérifier que le débogueur fonctionne

1. Ouvrir `cours/verif/test-debogage.php` dans VS Code.
2. Cliquer dans la marge, à gauche du numéro de la **ligne 19**
   (`$total = $total + $pointsDeLaManche;`). Un point rouge apparaît :
   c’est le **point d’arrêt**.
3. Appuyer sur `F5`, ou aller dans « Exécuter » puis « Démarrer le
   débogage », et choisir **Écouter Xdebug**. Une barre d’outils apparaît
   en haut de la fenêtre : VS Code est maintenant à l’écoute.
4. Dans le navigateur, ouvrir
   http://localhost:8080/verif/test-debogage.php

Résultat attendu : **la page ne s’affiche pas**, l’onglet du navigateur
tourne dans le vide, et VS Code passe au premier plan avec la ligne 19
surlignée en jaune.

Le débogueur fonctionne. Appuyer sur `F5` (« Continuer ») plusieurs fois
jusqu’à ce que la page s’affiche dans le navigateur, puis arrêter
l’écoute avec le carré rouge de la barre d’outils.

## 2. Lire ce que montre le débogueur

Une fois l’exécution arrêtée, le panneau de gauche affiche :

**Variables** — le contenu de chaque variable à cet instant précis.
Dans `Locals`, on voit `$total` (le total accumulé jusqu’ici, donc `0` au
premier arrêt), `$pointsDeLaManche` (la valeur du tour en cours, `12`),
`$numeroManche` (`0`) et `$points`, le tableau complet, qui se déplie
d’un clic sur la flèche. Relancer avec `F5` : ces valeurs changent à
chaque tour de boucle. C’est exactement ce qu’un `echo` aurait montré,
sans avoir à l’écrire.

**Espions** (`Watch`) — des expressions à surveiller. Cliquer sur `+` et
saisir par exemple `$total * 2` : le résultat est recalculé à chaque
arrêt.

**Pile d’appels** (`Call Stack`) — le chemin parcouru pour arriver là.
On y lit `totalPoints()` puis `{main}` : la fonction a été appelée depuis
le corps de la page. Cliquer sur une ligne pour remonter et voir les
variables à ce niveau.

## 3. Les commandes de déplacement

Ce sont les boutons de la barre d’outils, du plus courant au moins
courant.

| Touche    | Nom                 | Effet                                                |
| --------- | ------------------- | ---------------------------------------------------- |
| `F5`      | Continuer           | Repart jusqu’au prochain point d’arrêt               |
| `F10`     | Pas à pas principal | Ligne suivante, sans entrer dans les fonctions       |
| `F11`     | Pas à pas détaillé  | Ligne suivante, en entrant dans les fonctions        |
| `Maj+F11` | Pas à pas sortant   | Termine la fonction en cours et revient à l’appelant |
| `Maj+F5`  | Arrêter             | Coupe l’écoute du débogueur                          |

Pour s’exercer : retirer le point d’arrêt de la ligne 19, en poser un sur
la ligne `$total = totalPoints($points);`, recharger la page, puis
appuyer sur `F11`. Le débogueur entre dans la fonction. `Maj+F11` en
ressort.

## 4. Les erreurs affichées en clair, avec un lien vers le fichier

Sans rapport avec le point d’arrêt : Xdebug embellit aussi les erreurs
PHP (notices, warnings, erreurs fatales) qui s’affichent normalement dans
le navigateur, avec un tableau coloré et surtout le nom du fichier fautif
**cliquable**, qui ouvre directement la bonne ligne dans VS Code.

Pour le voir : ouvrir `cours/verif/test-debogage.php`, ajouter
temporairement une lettre à la fin de `$points` à la **ligne 28**
(`$total = totalPoints($points);` devient par exemple
`$total = totalPoints($pointss);`), enregistrer, puis recharger la page
dans le navigateur. Un tableau orange apparaît, avec le nom du fichier en
lien. Cliquer dessus ouvre VS Code à la ligne concernée. Annuler la
modification ensuite (`Ctrl+Z`).

Si le clic n’ouvre rien, ou ouvre le mauvais fichier, deux causes
possibles :

- **VS Code n’est pas encore associé au lien `vscode://`.** Ça se règle
  une fois, au niveau du système, en installant VS Code normalement
  (l’association se fait à l’installation).
- **`HOST_COURS_DIR` ne correspond pas au bon dossier, ou l’environnement
  (WSL, Remote-SSH...) n’est pas détecté.** Voir la section suivante,
  puis « Si ça ne fonctionne pas » plus bas.

Dans tous les cas, une solution de repli fonctionne toujours : le nom du
fichier et le numéro de ligne restent lisibles sur la page d’erreur.
Dans VS Code, `Ctrl+P` (ou `Cmd+P`), puis taper par exemple
`test-debogage.php:30`, ouvre directement la bonne ligne, sans passer par
le lien.

### Pourquoi le lien change selon l’environnement

Un lien `vscode://file/<chemin>` ne fonctionne que si VS Code tourne
directement sur la machine dont on lui donne le chemin. C’est le cas le
plus simple, mais pas le seul :

**Sous WSL**, VS Code tourne côté Windows,
connecté au dossier du projet via l’extension **Remote - WSL**. De son
point de vue, `/home/...` n’est pas un chemin Linux : c’est un chemin
Windows invalide, d’où une erreur du type « Le chemin
`\\home\...` n’existe pas sur cet ordinateur ». Le lien qui fonctionne
est différent : `vscode://vscode-remote/wsl+<distribution>/<chemin>`,
par exemple :

    vscode://vscode-remote/wsl+Ubuntu/home/user/.../test-debogage.php:19

Rien à faire pour l’obtenir : WSL définit déjà `WSL_DISTRO_NAME` dans le
shell (« Ubuntu » ici), `docker-compose.yml` la transmet au conteneur
sous le nom `HOST_WSL_DISTRO`, et `ouvrir-editeur.php` choisit ce format
automatiquement dès qu’elle est présente.

**En Remote-SSH** (si le projet tourne un jour sur un serveur distant,
avec VS Code qui s’y connecte par SSH), l’« autorité » attendue est
`ssh-remote+<alias>`, où `<alias>` est le nom donné à cette connexion
dans VS Code — propre à chaque poste, impossible à deviner
automatiquement. Pour l’activer, créer un fichier `.env` à la racine du
projet (non versionné, comme les autres identifiants — voir
`.gitignore`) :

    VSCODE_REMOTE_AUTHORITY=ssh-remote+mon-serveur

en remplaçant `mon-serveur` par l’alias exact utilisé dans VS Code, puis
redémarrer :

    docker compose down
    docker compose up -d

**Sinon** (Linux ou macOS, sans WSL ni Remote-SSH), aucun réglage n’est
nécessaire : le lien `vscode://file/<chemin>` fonctionne tel quel.

**Attention à un piège** : si le lien est mal construit, VS Code peut
proposer d’ajouter l’« hôte » qu’il croit y lire, avec une autorisation
permanente — par exemple un hôte nommé simplement `home` (extrait d’un
chemin `/home/...` mal interprété). Ce message ne signifie pas qu’il faut
autoriser quelque chose : il signifie que le lien est mal formé. Refuser
systématiquement une demande de ce genre pour un nom qui ne correspond à
rien de connu, puis corriger la configuration (`HOST_WSL_DISTRO`,
`VSCODE_REMOTE_AUTHORITY`) ou se rabattre sur `Ctrl+P`.

## Comment s’en servir dans son propre code

La démarche est toujours la même :

1. Repérer la ligne à partir de laquelle le résultat devient faux.
2. Y poser un point d’arrêt, un peu avant.
3. Lancer l’écoute (`F5`), recharger la page.
4. Avancer avec `F10` en surveillant le panneau « Variables », jusqu’à
   voir une variable prendre une valeur inattendue.

C’est cette valeur inattendue qui désigne l’erreur, presque toujours
quelques lignes plus haut que la ligne qui plantait.

Le point d’arrêt n’a rien de définitif : il ne modifie pas le fichier et
disparaît d’un clic. Il n’y a donc aucun risque à en poser.

## Si ça ne fonctionne pas

**`config-xdebug.php` indique une extension absente, ou un mode incorrect.**
Ce n’est pas un problème d’usage mais d’installation : reprendre
`ressources/VERIFICATION.md`, section 4.

**La page s’affiche normalement, VS Code ne réagit pas.**
Le débogueur n’écoutait pas. Vérifier que la barre d’outils de débogage
est bien présente en haut de VS Code avant de recharger la page. Recharger
ensuite avec `Ctrl+Maj+R` pour éviter une réponse gardée en cache par le
navigateur.

**Le point d’arrêt est un cercle gris et vide.**
VS Code ne relie pas le fichier ouvert à celui qu’exécute le serveur.
C’est le rôle de `pathMappings` dans `.vscode/launch.json` : le dossier
`cours` du poste correspond à `/var/www/html` dans le conteneur. Vérifier
que le dossier ouvert dans VS Code est bien la racine du projet, et non un
sous-dossier.

**Le port 9003 est déjà utilisé.**
Un autre débogueur écoute déjà, souvent une fenêtre de VS Code restée
ouverte. La fermer, ou arrêter la session en cours.

**Le lien sur une page d’erreur ouvre le mauvais dossier, ou rien.**
Ce lien s’appuie sur trois variables, calculées par `docker-compose.yml`
à partir de l’environnement dans lequel `docker compose` est lancé (voir
« Pourquoi le lien change selon l’environnement » ci-dessus). Vérifier
leur valeur :

    docker compose exec web sh -c 'echo $HOST_COURS_DIR; echo $HOST_WSL_DISTRO; echo $VSCODE_REMOTE_AUTHORITY'

- `HOST_COURS_DIR` doit être le chemin complet du dossier `cours` sur le
  poste. S’il est faux, `docker compose` n’a probablement pas été lancé
  depuis la racine du projet : redémarrer depuis le bon dossier
  (`docker compose down` puis `docker compose up -d`).
- `HOST_WSL_DISTRO` doit correspondre au nom de la distribution WSL
  utilisée (vide si on n’est pas sous WSL).
- `VSCODE_REMOTE_AUTHORITY` doit être vide, sauf réglage volontaire pour
  du Remote-SSH.

En dernier recours, le raccourci `Ctrl+P` décrit plus haut fonctionne
quel que soit l’état de ces variables.

**Rien de tout cela.**
Activer le journal de Xdebug : dans `docker/web/conf.d/xdebug.ini`,
remplacer `xdebug.log_level=0` par `xdebug.log_level=7`, puis :

    docker compose restart web
    docker compose exec web cat /tmp/xdebug.log

Le journal indique à qui Xdebug a tenté de se connecter et pourquoi cela a
échoué. Remettre `0` ensuite : le journal grossit vite.

## Où est réglée la configuration

Rien de tout cela n’est à modifier, mais il est utile de savoir où
regarder.

| Fichier                          | Contenu                                                                                 |
| -------------------------------- | --------------------------------------------------------------------------------------- |
| `docker/web/Dockerfile`          | Installation de l’extension Xdebug                                                      |
| `docker/web/conf.d/xdebug.ini`   | Réglages : mode, adresse et port de l’éditeur, lien des pages d’erreur                  |
| `docker-compose.yml`             | `extra_hosts`, `HOST_COURS_DIR`, `HOST_WSL_DISTRO`, `VSCODE_REMOTE_AUTHORITY`           |
| `.vscode/launch.json`            | Côté VS Code : port d’écoute et correspondance des chemins                              |
| `cours/verif/ouvrir-editeur.php` | Traduit le chemin des liens d’erreur (conteneur → poste), selon l’environnement détecté |

Le sens de la connexion surprend souvent : ce n’est pas VS Code qui va
chercher le serveur, c’est le serveur qui appelle VS Code sur le port
9003. C’est pour cela qu’il faut lancer l’écoute **avant** de recharger la
page.
