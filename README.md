# api-example

Example project using Symfony and API Platform.

## Requirements
- [Symfony CLI](https://symfony.com/download)
- [Composer](https://getcomposer.org/)

## Installation
Run the following after checking out project: 
```
composer install
./bin/console doctrine:database:create
./bin/console doctrine:migrations:migrate
./bin/console doctrine:fixtures:load
```

## Usage
```
symfony server:start
```

Go to the index page and test api endpoints.

You could also use a tool like https://insomnia.rest/.
