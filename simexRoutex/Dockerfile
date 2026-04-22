# Imagen de php (ligera), que procesa peticiones rápidamente
FROM php:8.2-fpm

# Instalamos nginx que sirve a la web y node para compilacion del front, aparte instalo los drives de sql (que se comentaba que no eran necesarios), para que las peticiones no peten y devuelvan un 500
# Activacion de modulos importantes como sqlsrv gd, zip y intl
RUN apt-get update && apt-get install -y nginx nodejs npm git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && P1="packages" && P2="microsoft.com" \
    && REPO="https://$P1.$P2/debian/11/prod" \
    && echo "deb [arch=amd64] $REPO bullseye main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update -o Acquire::AllowInsecureRepositories=true \
    && ACCEPT_EULA=Y apt-get install -y --allow-unauthenticated msodbcsql18 \
    && pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl


# php por defecto escucha en socket, otra dirección. Forzamos para que nginx sepa a donde dirigirse para procesar los archivos de php
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

# copiamos el composer para que pueda gestiona las librerias de php
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# mapeo de classes a produccón
RUN composer install --no-dev --optimize-autoloader

# instalacion de node modules y generacion de css y js
RUN npm install && npm run build

# Permisos para que laravel pueda escribir en storage -> 500
# nginx copia la configuración y crea enlace simbolico para acceder
# www-data evita bloqueos de escritura
RUN chmod -R 775 storage bootstrap/cache
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

RUN chown -R www-data:www-data /var/www

EXPOSE 80
CMD ["/start.sh"]
