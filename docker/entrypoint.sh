#!/bin/sh
set -e

cd /var/www/html

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

bootstrap_once() {
    echo "Waiting for MySQL..."
    until php -r "
        \$host = getenv('DB_HOST') ?: 'mysql';
        \$port = getenv('DB_PORT') ?: '3306';
        \$db   = getenv('DB_DATABASE') ?: 'laravel';
        \$user = getenv('DB_USERNAME') ?: 'laravel';
        \$pass = getenv('DB_PASSWORD') ?: 'secret';
        try {
            new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    "; do
        sleep 2
    done

    echo "MySQL is ready."

    if [ ! -f "vendor/autoload.php" ]; then
        composer install --no-interaction --prefer-dist --optimize-autoloader
    fi

    if [ -z "$(grep '^APP_KEY=base64:' .env 2>/dev/null || true)" ]; then
        php artisan key:generate --force --no-interaction
    fi

    npm install
    npm run copy-gentelella-assets

    if [ ! -f "public/build/manifest.json" ]; then
        npm run build
    fi

    php artisan migrate --force --no-interaction
    php artisan storage:link --force --no-interaction 2>/dev/null || true

    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

    touch .docker/bootstrapped
    echo "Bootstrap complete."
}

mkdir -p .docker

mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan storage:link --force --no-interaction 2>/dev/null || true

if [ ! -f ".docker/bootstrapped" ]; then
    bootstrap_once
fi

exec "$@"
