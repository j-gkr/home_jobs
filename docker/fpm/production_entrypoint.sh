#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

echo "install dependencies"
composer install --optimize-autoloader
php bin/console doctrine:migration:status
php bin/console doctrine:migration:migrate --no-interaction

exec "$@"