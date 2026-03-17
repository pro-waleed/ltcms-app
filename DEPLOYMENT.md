# Deployment Guide

## Current deployment target

The project is now prepared for container-based deployment.
The repository includes:

- `Dockerfile` for production container builds
- `render.yaml` for Render deployment
- `.env.example` with production-oriented defaults
- `/up` health check route from Laravel

## Before first deploy

Set these environment variables in your hosting platform:

- `APP_KEY`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Recommended values:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- `SESSION_SECURE_COOKIE=true`

## First-time database setup

Run migrations in production:

```bash
php artisan migrate --force
```

If this is the first deployment, seed the core roles and admin user:

```bash
php artisan db:seed --force
```

## Recommended production database

Use PostgreSQL for production.

Minimum database-backed tables required by the current app:

- `migrations`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- all LTCMS business tables from the domain migrations

The project now includes a dedicated migration for the `sessions` table, which is required because the app uses:

- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`

## Example production database variables

```bash
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=ltcms
DB_USERNAME=ltcms
DB_PASSWORD=your-strong-password
DB_SSLMODE=require
```

## Local container test

Build:

```bash
docker build -t ltcms-app .
```

Run:

```bash
docker run --rm -p 10000:10000 --env-file .env ltcms-app
```

## Notes

- The app currently serves through `php artisan serve` inside the container for simplicity.
- For higher traffic production setups, move later to Nginx or Caddy with PHP-FPM.
- Shared hosting or SQLite-only deployment is not recommended for long-term production use.
