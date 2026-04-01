# api-example

Example API project using Symfony 7.4 LTS and API Platform 4.3.

## Requirements
- PHP 8.4+
- [Composer](https://getcomposer.org/)

## Quick Start

1. Install dependencies:
```bash
composer install
```

2. Create `.env.local` with local overrides:
```bash
# Generate a random APP_SECRET
APP_SECRET=$(php -r "echo bin2hex(random_bytes(32));")

# Create .env.local
cat > .env.local <<EOF
APP_SECRET=$APP_SECRET
DATABASE_URL="sqlite:///%kernel.project_dir%/var/movies.db"
EOF
```

3. Setup database and load fixtures:
```bash
./bin/console doctrine:migrations:migrate
./bin/console doctrine:fixtures:load
```

4. Start the dev server:
```bash
symfony server:start
```

Access the API at `https://127.0.0.1:8000`

## Data Persistence Notes

- **Movie & Category**: Persisted to SQLite database (durable across server restarts)
- **Director**: In-memory storage only (data resets to defaults on server restart) — demonstrates state provider patterns without database

## Tests
```bash
# First-time setup (creates SQLite test DB)
APP_ENV=test php bin/console doctrine:schema:create

# Run all tests
./vendor/bin/codecept run

# Run only functional tests
./vendor/bin/codecept run functional
```
