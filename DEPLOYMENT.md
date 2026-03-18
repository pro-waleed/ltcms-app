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
- `SESSION_DRIVER=cookie`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`
- `SESSION_SECURE_COOKIE=true`

## First-time database setup

Run migrations in production:

```bash
php artisan migrate --force
```

If this is the first deployment only, seed the core roles and admin user once:

```bash
php artisan db:seed --force
```

Important:

- Do not run `db:seed` automatically on every deploy.
- The production container now runs a safe bootstrap seeder on startup to ensure roles, opportunity types, and the `admin` account exist without touching business data.
- Full application seeding remains disabled by default.
- The application now fails fast in production if it boots with `sqlite` instead of a persistent database such as PostgreSQL.
- If a deploy fails after this change, review the Render environment values first and confirm the web service is bound to the persistent PostgreSQL instance.
- If you explicitly need boot-time seeding for a fresh environment, temporarily set `SEED_CORE_DATA_ON_BOOT=true`, then return it to `false` after initial bootstrap.

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

For the current Render deployment, the recommended stable setup is:

- `SESSION_DRIVER=cookie`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`

This reduces startup and session persistence issues while the system is being stabilized on a single web instance.

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
- Production data must live in PostgreSQL, not inside the container filesystem.
