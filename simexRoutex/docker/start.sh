#!/bin/sh

# iniciar PHP-FPM en background
php-fpm -D

# iniciar nginx en foreground
nginx -g "daemon off;"
