#!/usr/bin/env bash
set -e

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs public/uploads

php artisan config:clear
php artisan view:clear
php artisan migrate --force
php artisan db:seed --force

export PHP_CLI_SERVER_WORKERS=${PHP_CLI_SERVER_WORKERS:-4}
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
