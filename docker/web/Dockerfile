# Image du serveur web (PHP + Apache).
#
# Pour ajouter une extension PHP, l'ajouter a la liste ci-dessous puis
# reconstruire l'image :
#
#   docker compose up -d --build

FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

# Debogueur pas a pas, utilise par VS Code (extension « PHP Debug »).
# docker-php-ext-enable ne fait qu'activer l'extension ; ses reglages
# sont dans conf.d/xdebug.ini.
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY conf.d/ /usr/local/etc/php/conf.d/

# Active le listage des dossiers (desactive par defaut par l'image
# officielle). But pedagogique : voir le contenu d'un dossier sans
# index.php/index.html aide l'etudiant a comprendre ses erreurs.
COPY apache/listage-dossiers.conf /etc/apache2/conf-available/
RUN a2enconf listage-dossiers
