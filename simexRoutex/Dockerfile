FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx nodejs npm git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && REPO="https://microsoft.com" \
    && echo "deb [arch=amd64] $REPO bullseye main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update -o Acquire::AllowInsecureRepositories=true \
    && ACCEPT_EULA=Y apt-get install -y --allow-unauthenticated msodbcsql18 \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl


RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

RUN chown -R www-data:www-data /var/www

EXPOSE 80
CMD ["/start.sh"]
