# Projet LivreToo

## Présentation

Workshop Symfony permettant de mettre en pratique les notions suivantes :

 - Utilisation du Composant Workflow et des Evènements
 - Création de Services Symfony
 - Consommer une Api avec le Symfony HttpClient

## Contexte

La société Livretoo s'occupe de mettre en relation via son application mobile, des clients et des restaurateurs. Elle propose à ses clients plusieurs services :

 - Mise en relation avec les utilisateurs de son application
 - Service de livraison à domicile.

## Problématique

Avec plusieurs restaurants, et plusieurs livraisons, Livretoo à maintenant besoin de mettre en place une plateforme permettant de gérer les commandes provenant des différents restaurants et clients.

## Consigne

Vous avez donc pour mission de développer l'application permettant à Livretoo de gérer correctement ses commandes et ses livraisons.

### Objectifs :

**Vous devez mettre en place les points suivants :**

 1. Sécurisation de l'application permettant aux utilisateurs *(Dispatcher, Restaurant)* de se connecter.
 
 2. Gestion des commandes entrante par un restaurant et  suivi de la commande jusqu’à sa livraison au client.

> ATTENTION : Il n'y a pas d'accès client ou livreur a développer.

 3. Syncronisation des données de l'Api de livraison avec le workflow lors de la mise à jour des informations par les livreurs.

> NOTA BENE : Concentrez-vous sur le développement. Le graphisme n'a pas d'importance.


### La Livraison

Pour assurer une bonne répartition métier, le SI des livraisons est assuré par une API.
Les livreurs disposant d'une application mobile, mettent directement à jour leur livraison via l'application connecté à l'API.

## Organisation

Avant votre arrivée, un stagiaire à déjà commencé à mettre en place certaines entités. 
A vous de créer / mettre à jour les entités, contrôleurs pour répondre aux objectifs.

### Etape 1 (3 heures) :

- Login Form + Authentication
- Création du Workflow (workflow.yaml) : Places et Transitions. Génération de votre graph.

### Etape 2 (3 heures) :

- Traitement du workflow d'une commande *(Evenements Workflow à la création d'une commande : app:new-order)*
- Mise en place de **l'interface Restaurateur** : Voir les commandes du restaurant et accepter / refuser une commande.
- Mise en place de **l'interface Dispatcher** : Voir toutes commandes des restaurants et visualiser les statuts.
- Pensez aux notifications email. *(MailCatcher ou MailTrap)*

### Etape 3 (3 heures) :

- Dans les events grace a HttpClient consommer l'api de livraison.
- Syncroniser votre workflow avec l'API lorsqu'un livreur à terminer sa livraison.

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


## Api de Livraison

L'api de livraison est disponible à l'adresse suivante et ne nécessite pas d'authentification.

    http://api.biyn.media/api

## Mot de passe

Tous les mots de passe utilisateurs sont : `livretoo`
