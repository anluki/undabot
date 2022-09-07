# PreRequirement:

Install MySql, php7.4, phpMyAdmin, Symfony CLI, Composer, Apache, Postman 

# Setting up project:
- Download project from git repository: git clone 
- Go to root of project folder and run command in terminal: composer install to download all dependencies needed to run project
- run command php bin/console doctrine:migrations:migrate to run migration
- run command symfony server:start to run symfony application
- import Undabot.postman_collection.json (located in root folder of application) into Postman to test rest api
