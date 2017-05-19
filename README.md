simplon_project
===============

A faire à chaque déploiement :

$ git clone https://github.com/MaksJS/simplon_symfony.git

$ composer install

$ bower install

$ php bin/console doctrine:schema:create

$ php bin/console doctrine:fixtures:load

$ php bin/console faker:populate

$ php bin/console server:start

Doctrine :

$ php bin/console doctrine:schema:validate

$ php bin/console doctrine:schema:update --force

Génération :

$ php bin/console generate:doctrine:crud --entity=AppBundle:Product --format=annotation --with-write --no-interaction

$ php bin/console generate:doctrine:entity 

$ php bin/console generate:doctrine:entities AppBundle 

$ php bin/console generate:doctrine:form AppBundle:Product