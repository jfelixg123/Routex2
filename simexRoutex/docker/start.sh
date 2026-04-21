#!/bin/sh

echo "starting php-fpm..."
php-fpm

echo "starting nginx..."
nginx -g "daemon off;"
