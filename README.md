# Projet LivreToo

## Présentation

Workshop Symfony permettant de mettre en pratique les notions suivantes :

 - Utilisation du Composant Workflow et des Evènements
 - Création de Services Symfony
 - Consommer une Api avec le Symfony HttpClient

## Installation

Installation des Dépendances via Composer

    composer install

Mettre à jour votre fichier .env

    DATABASE_URL=mysql://db_user:db_pass@127.0.0.1:3306/db_name

Créer la base de donnée :

    php bin/console doctrine:database:create

Appliquer la migration :

    php bin/console doctrine:migrations:migrate

Installez les fixtures :

     php bin/console doctrine:fixtures:load

Simuler l'arrivée d'une commande d'un client :

    php bin/console app:new-order
