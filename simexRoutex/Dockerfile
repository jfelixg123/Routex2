# ---------- NODE BUILD (frontend opcional) ----------
FROM node:20-alpine AS build-stage

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ---------- PHP ----------
FROM php:8.2-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl unzip git zip \
    libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto
COPY . .

# Copiar build frontend (si existe)
COPY --from=build-stage /app/public/build ./public/build

# Instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos Laravel
RUN chmod -R 775 storage bootstrap/cache

# IMPORTANTE: PHP-FPM escucha en 9000
EXPOSE 9000

# Arranque del contenedor
CMD ["php-fpm"]
