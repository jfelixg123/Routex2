# -------------------------------
# ETAPA 1: Build frontend (Node)
# -------------------------------
FROM node:20-alpine AS build-stage
WORKDIR /app
# Copiamos archivos de dependencias
COPY package*.json ./
RUN npm install
# Copiamos el resto del código para compilar
COPY . .
RUN npm run build

# -------------------------------
# ETAPA 2: PHP + SQL Server
# -------------------------------
FROM php:8.2-fpm

# Instalar dependencias del sistema y herramientas necesarias
RUN apt-get update && apt-get install -y \
    curl gnupg unzip git zip libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && curl -fsSL https://nodesource.com | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Instalar drivers SQL Server (Microsoft)
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://microsoft.com | gpg --dearmor -o /etc/apt/keyrings/microsoft.gpg \
    && echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/microsoft.gpg] https://microsoft.com bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

ENV PATH="$PATH:/opt/mssql-tools18/bin"

# Instalar extensiones PHP necesarias para Laravel y SQL Server
RUN docker-php-ext-install mbstring pcntl bcmath gd zip intl pdo_mysql \
    && pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar el código del proyecto
COPY . .

# Eliminar builds previos y copiar el nuevo desde la etapa anterior
RUN rm -rf public/build
COPY --from=build-stage /app/public/build ./public/build

# Instalar dependencias de PHP (Optimizado para producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto 9000 (puerto por defecto de PHP-FPM)
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
