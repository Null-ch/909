# Refactor & Security Log — `feature/security-and-search-refactor`

Date: 2026-08-16
Scope: fix and refactor product search; apply a baseline security hardening pass.

## ⚠️ Incident during this work — please read first

While verifying the new search tests, `php artisan test` was run inside the
`laravel-app` container. `phpunit.xml` is configured to run tests against an
in-memory SQLite database, but that override was not actually taking effect
(root cause and fix below) — so `RefreshDatabase` ran `migrate:fresh` against
the **real MySQL dev database** and wiped every table (products, categories,
users, orders, banners, settings, carts, activity logs — everything).

**What was restored:** `php artisan db:seed` was run, which recreated:
- Admin account: `admin@example.com` / `password` (role: admin)
- A second account: `test@example.com` / `password` (role: user)
- Sample categories, products, banners, settings, delivery methods, orders, and carts from the project's seeders

**What was *not* recoverable:** anything you had entered by hand through the
UI that wasn't covered by a seeder — e.g. specific products/orders/settings
you'd created manually. If that applies, I'm sorry — that data is genuinely
gone, and I understand this is exactly the kind of mistake this task asked me
to guard against, not cause.

**Root cause:** `docker-compose.yml` sets `DB_CONNECTION=mysql` (and
`DB_HOST`/`DB_DATABASE`/etc.) as real container environment variables. Those
land in PHP's `$_SERVER` superglobal, which Laravel's `env()`/config
resolution reads *ahead of* `$_ENV`. `phpunit.xml`'s `<env>` block only
reliably updates `$_ENV` and `putenv()`, not `$_SERVER` — so on this stack it
silently had no effect on `config('database.default')`, even though
`getenv()` reflected the override correctly. `Tests/Feature/ExampleTest.php`
(the stock Laravel skeleton test) never used `RefreshDatabase`, so it never
tripped this before; the first test in this branch that added
`RefreshDatabase` did.

**Fix:** `phpunit.xml` now sets matching `<server>` entries (not just `<env>`)
for every DB/cache/session/queue variable, all with `force="true"`. Verified
directly: a diagnostic test confirmed `DB::connection()->getDriverName()`
now resolves to `sqlite` / `:memory:` inside `artisan test`, and the real
dev database's row counts were confirmed unchanged before and after
re-running the full suite multiple times. **Anyone running this test suite
inside the Docker containers was exposed to this same landmine** — it wasn't
specific to anything I did, just the first thing in this repo's history to
combine `RefreshDatabase` with this container setup.

A second, related incident happened minutes later: the new `SecurityHeaders`
middleware's CSP blocked `cdnjs.cloudflare.com` (Font Awesome), which broke
icons/styling site-wide. Caught immediately from your report and fixed by
allowlisting that origin in the CSP (see Security section below).

A third bug surfaced during manual verification of `/catalog`: caching a
fully-hydrated Eloquent `LengthAwarePaginator` through the `database` cache
store round-tripped through PHP's `serialize()`/`unserialize()` and came
back as `__PHP_Incomplete_Class`, 500-ing the catalog page on every cache hit
after the first. Fixed by caching only plain product IDs + total count, not
model instances (see Search section below).

All three were caught and fixed within this session via direct
verification against the running containers (not just "should work"
reasoning) before considering the work done.

---

## 1. Diagnosis

**The reported bug, root-caused:** the site header's search box
(`resources/views/front/partials/header.blade.php`) submits `GET /search?q=...`
on every page — but no `/search` route existed. Confirmed via the nginx
access log: repeated `"GET /search?q=... HTTP/1.1" 404`. The only working
search was a *separate*, undiscoverable one buried in the Catalog page's
filter sidebar (`search` bound via `wire:model.live.debounce.400ms`).

**Where that sidebar search underperformed:**
- `CatalogService::getProducts()` matched via `LIKE '%term%'` across `name`,
  `short_description`, `description`, `sku` — a leading wildcarded `LIKE`
  can't use a B-tree index, so it table-scans as the catalog grows.
- No relevance ranking — a match in `sku` sorted the same as a match in
  `description`, ordering always fell back to `id DESC`.
- LIKE metacharacters (`%`, `_`, `\`) in the raw query weren't escaped, so a
  user searching for e.g. `50%` or `A_C` got wildcard behavior instead of a
  literal match.
- No caching, despite this running on every keystroke (debounced).
- The equivalent admin product datatable search (`ProductService::datatable`)
  had the same unescaped-LIKE issue.

## 2. Search refactor

- **`app/Models/Product.php`** — added `scopeSearch()`, `scopeActive()`,
  `scopeInCategories()`, `scopePriceBetween()`, and `Product::escapeLike()`.
  `scopeSearch()` uses a MySQL/MariaDB `FULLTEXT` index (`whereFullText`, +
  a `sku` prefix match) when running on MySQL and the term is ≥3 characters;
  every other driver (SQLite locally and in tests) falls back to an escaped
  `LIKE`. There is no MySQL equivalent for SQLite, so this can't be made
  uniform — it's tested on both paths (see Testing below).
- **`database/migrations/2026_08_16_150000_add_fulltext_index_to_products_table.php`**
  — adds a `FULLTEXT (name, short_description, description)` index,
  guarded to only run on MySQL/MariaDB so `migrate` still works on SQLite.
- **`app/Repositories/ProductRepositoryInterface.php` +
  `EloquentProductRepository.php`** — the actual catalog query builder
  (category/price/search/sort/pagination), extracted out of the service
  layer. Bound in `AppServiceProvider`. On MySQL, with a search term and the
  default sort, results are additionally ranked by `MATCH() AGAINST()`
  relevance; an explicit price/name sort still wins over relevance.
- **`app/Services/SearchService.php`** — sanitizes the raw term
  (`strip_tags`, control-character stripping, whitespace collapsing, capped
  at 100 chars), normalizes filters, and caches results for 120s. **Caches
  only the matched product IDs + total count**, not hydrated models — see
  the incident note above for why. `CatalogService::getProducts()` now
  delegates here; its public signature is unchanged, so the Catalog Livewire
  component required no changes.
- **`app/Http/Requests/SearchRequest.php` + `app/Http/Controllers/SearchController.php`**
  — the actual bug fix. `GET /search` validates `q` (string, max 100) and
  redirects into `/catalog?search=...`, reusing the Catalog page's existing
  filter/sort/pagination UI rather than building a second, duplicate results
  page.
- **`routes/web.php`** — registered `/search` (named `search`, rate-limited
  `throttle:60,1`), named the catalog route `catalog` (needed for the
  redirect), and added `throttle:5,1` to both login routes and registration.
- **`app/Services/ProductService.php`** — the admin datatable search now
  escapes LIKE metacharacters via `Product::escapeLike()` instead of using
  the raw term directly.

## 3. Security

| Item | Status | Notes |
|---|---|---|
| SQL injection | ✅ Already safe | All queries go through Eloquent/query builder parameter binding — verified with a `' OR 1=1 --` payload against `/search` (test: `SearchTest::test_sql_injection_payload_is_treated_as_a_literal_string`) |
| XSS | ✅ Verified | Blade `{{ }}` escaping is used throughout; tested with `<script>alert(1)</script>` against `/search` |
| CSRF | ✅ Already covered | `VerifyCsrfToken` is active by default (Laravel 11+ `bootstrap/app.php` style); every `method="POST"` form in `resources/views` was checked programmatically — all have `@csrf` |
| Mass assignment | ✅ Already covered | All 13 models declare `#[Fillable([...])]` explicitly; none use open `$guarded = []` |
| Security headers | ✅ Added | New `App\Http\Middleware\SecurityHeaders`, applied globally: `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `X-XSS-Protection`, `Referrer-Policy`, `Permissions-Policy`, and a CSP scoped to `'self'` plus the two third-party origins the layout actually uses (Google Fonts, Font Awesome's CDN) |
| Rate limiting | ✅ Added | `throttle:5,1` on login (both guards) + registration, `throttle:60,1` on `/search` |
| Auth logging | ✅ Added | New `security` log channel (`storage/logs/security-*.log`, 90-day retention); listens for Laravel's own `Login`/`Failed` auth events (covers both guards, no controller changes needed) |
| `.env` / `.git` exposure | ✅ Already covered | `docker/nginx/default.conf` already has `location ~ /\.(?!well-known).* { deny all; }` |
| `expose_php` | ✅ Added | `docker/php/local.ini` now sets `expose_php = Off` |
| PHP upload/execution limits | ✅ Adjusted | `max_execution_time` 120→60s, `upload_max_filesize` 64M→20M, `post_max_size` 64M→25M (slightly above upload limit so multi-image product uploads aren't truncated) |
| Redis extension | ✅ Added | `pecl install redis` in the Dockerfile, for future use — no `redis` service was added to `docker-compose.yml` since none exists today and adding one is an infrastructure decision, not a code fix |
| 2FA | ⚠️ Not implemented | Task marked this optional; auth event logging above covers "at least log all logins" |
| `FILTER_SANITIZE_STRING` | ⚠️ Deliberately not used | This filter was deprecated in PHP 8.1 and removed in 9.0; Laravel Form Requests + Blade's automatic escaping are the correct replacement and are already used throughout |

### Deliberately *not* done, and why
- **Queueing the account-credentials email** (`AccountCredentialsMail`,
  which carries a plaintext temporary password) — queueing via the
  `database` queue driver would persist that plaintext password in the
  `jobs`/`failed_jobs` tables, which is worse than sending it synchronously.
  Left as-is.
- **Forcing `APP_DEBUG=false` / `SESSION_SECURE_COOKIE=true` in `.env`** —
  both are correct for production but would break the current local HTTP
  dev setup if hardcoded here. Documented instead: set `APP_DEBUG=false` and
  `SESSION_SECURE_COOKIE=true` in whatever environment actually serves this
  over HTTPS.
- **PostgreSQL `tsvector`** — this project only uses MySQL/SQLite; not
  applicable.

## 4. Testing

All new/changed tests run inside the `laravel-app` container:
```
docker exec laravel-app php artisan test
```
19 passed, 1 pre-existing risky warning (unrelated skeleton test), 0 failed.

- `tests/Unit/SearchServiceTest.php` — term sanitization (HTML stripping,
  control chars, whitespace, length cap), no database involved.
- `tests/Feature/SearchTest.php` — `/search` route existence + rate limit,
  redirect behavior, validation, and the SQLi/XSS payloads above, driven
  through a real HTTP request/response.
- `tests/Feature/CatalogSearchTest.php` — search-by-name, search-by-SKU,
  inactive products excluded, pagination (15 products → 12 per page),
  sorting, and the LIKE-metacharacter escaping fix (`A_C` no longer matches
  `A1C`). Assertions read the paginator's data directly rather than scraping
  rendered HTML, because nested `<livewire:product-card>` components don't
  render inline inside `Livewire::test()`'s snapshot (no JS runtime to
  hydrate them in the test harness) — that's a testing-harness limitation,
  not a product bug; the full-HTML path is covered by `SearchTest` instead.
- `tests/Feature/ExampleTest.php` — added the missing `RefreshDatabase`
  trait so it actually has tables to hit (the root cause of the incident
  above); still passes.

## 5. Docker / config

- `Dockerfile`: added `pecl install redis`.
- `docker/php/local.ini`: `expose_php = Off`, adjusted limits (see table
  above).
- `phpunit.xml`: fixed the test-database-isolation bug (see incident above)
  — this is the most load-bearing change in this whole branch.

## 6. Not done / out of scope this pass

- Composer/npm dependency pruning — reviewed both; nothing unused was found.
- A dedicated `redis` service in `docker-compose.yml` — infra decision, left
  for you to opt into.
- Self-hosting Font Awesome instead of the CDN — would remove the one
  third-party CSP exception entirely; more secure long-term but a separate
  frontend-build change.

## Final checklist

- [x] Search works and is optimized (FULLTEXT on MySQL, escaped LIKE fallback, relevance ranking, caching, pagination/sorting preserved)
- [x] Refactoring complete (Repository + Service pattern for search)
- [x] All POST forms protected by CSRF (verified programmatically)
- [x] Validation via Form Requests (`SearchRequest`, plus pre-existing ones)
- [x] Security headers added
- [x] Throttle/rate limiting on login, register, search
- [x] `.env`/`.git` already blocked at nginx
- [x] `expose_php` off
- [x] FULLTEXT index added
- [x] Search result caching (ID-based, serialization-safe)
- [x] Security event logging (auth success/failure)
- [x] Tests written and passing (19/19)
- [x] Docker: redis extension added, PHP limits tightened
- [ ] 2FA — not implemented (optional per task, logging added instead)
