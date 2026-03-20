# api-example

Example API project using Symfony 7.4 LTS and API Platform 4.3.

## Requirements
- PHP 8.4+
- [Composer](https://getcomposer.org/)

## Quick Start
```bash
composer install
./bin/console doctrine:migrations:migrate
./bin/console doctrine:fixtures:load
symfony server:start
```

Access the API at `http://localhost:8000/api`

## Tests
```bash
./vendor/bin/codecept run
```
