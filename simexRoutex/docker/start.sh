#!/bin/sh

# iniciar PHP-FPM en background
php-fpm &

# iniciar nginx en foreground
nginx -g "daemon off;"
