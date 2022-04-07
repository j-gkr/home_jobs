#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

php bin/console cron:run 1>> /dev/null 2>&1

exec "$@"