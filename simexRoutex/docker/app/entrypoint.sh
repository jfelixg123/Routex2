#!/bin/bash

# Salir inmediatamente si un comando falla
set -e

# Crear el enlace simbólico de Laravel (storage -> public)
# Usamos --force para que no falle si ya existe
echo "Cerrando brechas: Creando enlace simbólico de storage..."
php artisan storage:link --force

# Dar permisos a las carpetas de storage (por si acaso)
chmod -R 775 /var/www/storage /var/www/public/storage
chown -R www-data:www-data /var/www/storage /var/www/public/storage

# Ejecutar el proceso principal del contenedor (PHP-FPM)
echo "Arrancando orquesta: PHP-FPM listo."
exec php-fpm
