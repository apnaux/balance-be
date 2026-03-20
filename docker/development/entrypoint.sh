#!/bin/bash

# migrate and seed database
php artisan migrate && php artisan db:seed

# run php-fpm
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf &

# run front-end dev server
npm run dev &

# optimize
php artisan optimize

# Caddy in the foreground
caddy run --config /etc/caddy/Caddyfile
