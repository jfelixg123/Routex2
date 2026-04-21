#!/bin/sh

echo "starting php-fpm..."
php-fpm -D

echo "waiting..."
sleep 2

echo "starting nginx..."
nginx -g "daemon off;"
