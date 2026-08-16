# GAZONI

A Laravel 13 e-commerce storefront: product catalog, cart, checkout, delivery methods, and an admin panel (products, categories, orders, banners, settings, activity log), built with Livewire and Tailwind/Vite.

## Stack

- PHP 8.3, Laravel 13, Livewire 4
- MySQL 8.4 (Docker) or SQLite (local dev)
- Vite + Tailwind CSS 4 for front-end assets
- nginx + php-fpm (Docker)

## Ports used by this project

**All ports are non-default and configurable via `.env`.** They were chosen to avoid clashing with the containers already running on this server (`cabinet_frontend:3020`, `remnawave_bot:8080`, `remnawave:3000-3001`, `remnawave-db:6767`, `remnawave-subscription-page:3010`).

| Service | Env var | Default | Host bind |
|---|---|---|---|
| App (nginx, HTTP) | `APP_PORT` | `8888` | `127.0.0.1` only — put a reverse proxy in front for public/domain access |
| MySQL | `FORWARD_DB_PORT` | `33061` | `127.0.0.1` only (not reachable from outside the host) |

Before starting the stack, confirm these are actually free on your host:

```bash
ss -tulpn | grep -E ':8888|:33061'
```

If either port is in use, set `APP_PORT` / `FORWARD_DB_PORT` in `.env` to something else before running `docker compose up`. Nothing else in the stack (php-fpm, the app container) publishes a port to the host — nginx and MySQL are the only entry points.

> The app container talks to MySQL over the internal Docker network (`mysql:3306`), so changing `FORWARD_DB_PORT` only affects host access (e.g. connecting a local DB client) — it does not need to match anything the app itself uses.

## Option A — Docker (recommended for this server)

Requirements: Docker + Docker Compose plugin.

1. Copy the environment file and generate an app key placeholder:
   ```bash
   cp .env.example .env
   ```
2. Review `.env` — at minimum set `APP_URL` to how you'll reach the app (e.g. `http://your-server-ip:8888` or a domain if you're putting a reverse proxy in front), and change `DB_PASSWORD` from the default `secret`.
3. Start the stack:
   ```bash
   docker compose up -d --build
   ```
   The `app` container's entrypoint ([docker/entrypoint.sh](docker/entrypoint.sh)) automatically, on first boot:
   - waits for MySQL to be healthy
   - runs `composer install`
   - generates `APP_KEY` if missing
   - runs `npm install` and `npm run build` (compiles front-end assets)
   - runs migrations (`php artisan migrate --force`)
   - links `storage` for public file access

   This first run can take a few minutes (asset build + composer install). Subsequent restarts skip all of this (tracked via `.docker/bootstrapped`).
4. Visit `http://<server-ip>:8888` (or whatever `APP_PORT` you set).
5. Seed demo data (products, categories, an admin account, etc.) — optional, useful for a fresh non-production instance:
   ```bash
   docker compose exec app php artisan db:seed
   ```
   This creates:
   - Admin: `admin@example.com` / `password` (log in at `/admin/login`)
   - Regular user: `test@example.com` / `password`

   **Change or remove these accounts before exposing the site publicly.**

### Useful commands

```bash
docker compose logs -f app          # app/entrypoint logs
docker compose exec app bash        # shell into the app container
docker compose exec app php artisan migrate      # run new migrations
docker compose exec app php artisan tinker       # REPL
docker compose exec app npm run build            # rebuild front-end assets after asset changes
docker compose down                 # stop (keeps volumes/data)
docker compose down -v              # stop and wipe DB/volumes — destructive
```

### Reverse proxy / TLS

The `nginx` container only serves plain HTTP, bound to `127.0.0.1:${APP_PORT}` (not reachable from outside the host). To reach it at a domain with HTTPS, put your own reverse proxy (e.g. Caddy, or another nginx) on the host in front of that address and terminate TLS there — e.g. proxy `grass.fqknscp.digital` → `127.0.0.1:8888`.

Laravel is already configured to trust that proxy (`bootstrap/app.php` calls `trustProxies(at: '*')`, safe here since nothing but the local proxy can reach the app port), so `X-Forwarded-Proto` from the proxy is honored for HTTPS detection. Once the domain + TLS are live, also set in `.env`:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://grass.fqknscp.digital
SESSION_SECURE_COOKIE=true
```

## Option B — Local development without Docker

Useful for coding on the app directly rather than running it as a deployed service.

Requirements: PHP 8.3+, Composer, Node.js 22+, and either SQLite (default) or a local MySQL server.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # only if DB_CONNECTION=sqlite
php artisan migrate
npm install
npm run build          # one-off asset build, or:
npm run dev             # Vite dev server with hot reload
```

Then serve the app on a non-default port to keep it out of the way of anything else on your machine:

```bash
php artisan serve --port=8891
```

Run `npm run dev` in a separate terminal while developing so Blade/CSS/JS changes hot-reload.

## Environment reference

Key variables in `.env` (see [.env.example](.env.example) for the full list):

| Variable | Purpose |
|---|---|
| `APP_URL` | Public base URL — must match how you actually access the app, or asset URLs/redirects will be wrong |
| `APP_PORT` | Host port nginx binds to (Docker only) |
| `DB_CONNECTION` | `mysql` (Docker) or `sqlite` (local dev default) |
| `FORWARD_DB_PORT` | Host port MySQL binds to, for connecting external DB tools (Docker only) |
| `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | MySQL credentials — change the default password before any real deployment |

## Notes

- No queue worker or scheduler is currently used by the app (no `ShouldQueue` jobs, no scheduled commands), so the Docker stack doesn't run one — `php-fpm` alone is sufficient.
- Content-Security-Policy is enforced via `App\Http\Middleware\SecurityHeaders`. If you add new external script/style/font sources, allowlist them there or they'll be silently blocked by the browser.
- See [REFACTOR_LOG.md](REFACTOR_LOG.md) for a detailed history of a recent security/search refactor, including a documented incident and its fix — worth reading before running the test suite against a database you care about (`RefreshDatabase` tests are configured to use in-memory SQLite via `phpunit.xml`, not your dev database, but double-check this if you change DB config).
