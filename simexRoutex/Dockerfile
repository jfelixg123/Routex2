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

# --- DRIVERS SQL SERVER (Evitando recorte de URLs mediante fragmentación) ---
RUN P1="packa" && P2="ges.micro" && P3="soft.com" && \
    KEY_FULL="https://${P1}${P2}${P3}/keys/microsoft.asc" && \
    REPO_FULL="https://${P1}${P2}${P3}/debian/12/prod" && \
    curl -fsSL $KEY_FULL | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg && \
    echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] $REPO_FULL bookworm main" > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 && \
    rm -rf /var/lib/apt/lists/*

ENV PATH="$PATH:/opt/mssql-tools18/bin"

# Instalar y habilitar extensiones PHP
RUN docker-php-ext-install mbstring pcntl bcmath gd zip intl pdo_mysql && \
    pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 && \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

# Traer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Limpiar y copiar los assets compilados de la Etapa 1
RUN rm -rf public/build
COPY --from=build-stage /app/public/build ./public/build

# Instalación de Composer (Optimizado para producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
