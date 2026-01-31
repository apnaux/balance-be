#!/bin/bash

# Run caddy in the background
caddy run --config /etc/caddy/Caddyfile &addy

# migrate and seed database
php artisan migrate && php artisan db:seed

# run php-fpm
php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.d/www.conf &
