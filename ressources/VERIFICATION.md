# Vérifier son installation

À faire une fois, en tout début de semestre.

## 1. L’éditeur et le navigateur

Ouvrir `cours/verif/verification.html` directement dans le navigateur
(double-clic sur le fichier, ou clic droit dans VS Code puis « Show
Preview »). Cette page ne passe pas par le serveur : elle doit fonctionner
même sans Docker.

Le paragraphe « Ce paragraphe doit apparaître en vert » doit être vert et
en gras (feuille de style chargée), et le second paragraphe doit annoncer
que le script a été exécuté (fichier JavaScript chargé). Si l’un des deux
reste tel quel, le message affiché indique lequel des deux fichiers
(`verification.css` ou `verification.js`) n’a pas été trouvé.

## 2. Le serveur répond

Ouvrir http://localhost:8080/verif/verification.php (Docker) ou l’adresse
indiquée pour WAMP ou XAMPP.

La page doit afficher un tableau de résultats. Si le navigateur propose
de télécharger le fichier au lieu de l’afficher, la page n’a pas été
traitée par le serveur : vérifier que l’adresse commence bien par
« http:// » et non « file:// ».

## 3. La base répond

La même page indique si la connexion à la base fonctionne et combien
d’équipes y sont enregistrées. Le résultat attendu est six équipes,
après import du fichier fourni.

## 4. phpMyAdmin

Ouvrir http://localhost:8081 (Docker) ou l’adresse indiquée pour WAMP ou
XAMPP, puis vérifier que la base `esport` apparaît dans la liste de
gauche.

## 5. Le débogueur

Deux éléments distincts doivent être en place :

- côté serveur, l’extension **Xdebug** ;
- côté VS Code, l’extension **PHP Debug** (`xdebug.php-debug`) — normalement
  déjà installée en suivant les « Premiers pas » du `README.md` racine.

Ouvrir http://localhost:8080/verif/config-xdebug.php : les lignes
« Extension Xdebug » et « Serveur prêt pour le débogage » doivent être
vertes.

Une fois ces deux éléments en place, `cours/verif/README.md` explique
comment se servir du débogueur pendant les séances — inutile de le lire
avant.

## Si quelque chose ne fonctionne pas

Noter le message d’erreur exact avant de demander de l’aide : c’est lui
qui contient l’information utile, bien plus que la description de ce qui
ne marche pas.
