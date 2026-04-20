# -------------------------------
# ETAPA 1: Build frontend (Node)
# -------------------------------
FROM node:20-alpine AS build-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# -------------------------------
# ETAPA 2: PHP + SQL Server
# -------------------------------
FROM php:8.2-fpm

# Instalar dependencias esenciales
RUN apt-get update && apt-get install -y \
    curl gnupg unzip git zip libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# --- DRIVERS SQL SERVER (Sintaxis ultra-segura) ---
RUN curl -fsSL https://microsoft.com | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://microsoft.com bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

ENV PATH="$PATH:/opt/mssql-tools18/bin"

# Extensiones PHP
RUN docker-php-ext-install mbstring pcntl bcmath gd zip intl pdo_mysql \
    && pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Assets
RUN rm -rf public/build
COPY --from=build-stage /app/public/build ./public/build

# Composer e Instalación
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos críticos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
