#!/bin/sh


php-fpm -D #inicia motor php
sleep 2 #timmer para deja php iniciarse
nginx -g "daemon off;" #Inicia el nginx y lo mantiene
