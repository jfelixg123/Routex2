# -------------------------------
# ETAPA 1: Build frontend (Node)
# -------------------------------
FROM node:20-alpine AS build-stage
WORKDIR /app

# Copiamos archivos de dependencias y compilamos assets
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# -------------------------------
# ETAPA 2: PHP + SQL Server
# -------------------------------
FROM php:8.2-fpm

# Instalar dependencias esenciales del sistema
RUN apt-get update && apt-get install -y \
    curl gnupg unzip git zip libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar drivers oficiales de Microsoft para SQL Server
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://microsoft.com | gpg --dearmor -o /etc/apt/keyrings/microsoft.gpg \
    && echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/microsoft.gpg] https://microsoft.com bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

ENV PATH="$PATH:/opt/mssql-tools18/bin"

# Instalar y habilitar extensiones PHP
RUN docker-php-ext-install mbstring pcntl bcmath gd zip intl pdo_mysql \
    && pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Traer Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo en el contenedor
WORKDIR /var/www

# Copiar el código del proyecto
COPY . .

# Limpiar posibles restos y copiar los assets compilados de la Etapa 1
RUN rm -rf public/build
COPY --from=build-stage /app/public/build ./public/build

# Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ajustar permisos para carpetas de escritura de Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Puerto interno para comunicación con Nginx
EXPOSE 9000

CMD ["php-fpm"]
