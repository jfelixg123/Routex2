FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx nodejs npm git curl zip unzip \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl

RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
