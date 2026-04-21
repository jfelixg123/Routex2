FROM php:8.2-cli

# Nginx + dependencias
RUN apt-get update && apt-get install -y \
    nginx \
    curl unzip git zip \
    libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto
COPY . .

# Instalar Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Nginx config
COPY ./docker/nginx/conf.d/default.conf /etc/nginx/http.d/default.conf

EXPOSE 80

CMD sh -c "php -S 0.0.0.0:80 -t public"
