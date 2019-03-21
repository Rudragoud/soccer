# soccer
Following are steps set up environment

1. Clone project from git with repo.
2. cd project_name anddo the composer install
3. Change .env file database credentails 
4. execute following command in console to create database - php bin/console doctrine:database:create
5. execute following command to deploy schema - php bin/console doctrine:database:create --force (migration are suppoted)
6. Run unit test - ./bin/phpunit 

To test API, Please use Postman file attached in email.
