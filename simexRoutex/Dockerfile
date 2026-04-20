# -------------------------------
# ETAPA 1: Build frontend  (Node)
# -------------------------------
FROM node:20 AS build-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# -------------------------------
# ETAPA 2: PHP + SQL Server
# -------------------------------
FROM php:8.2-fpm

# Instalar dependencias del sistema y Node
RUN apt-get update && apt-get install -y \
    curl gnupg unzip git zip libpng-dev libzip-dev libonig-dev libxml2-dev libicu-dev unixodbc-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql gd zip

# Instalar drivers SQL Server
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /etc/apt/keyrings/microsoft.gpg \
    && echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

ENV PATH="$PATH:/opt/mssql-tools18/bin"

# Extensiones PHP
RUN docker-php-ext-install mbstring pcntl bcmath gd zip intl \
    && pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# --- EL ORDEN IMPORTANTE AQUÍ ---
COPY . .
# Borramos cualquier rastro del build local que pudiera venir en el COPY anterior
RUN rm -rf public/build
# Traemos el build BUENO de la ETAPA 1
COPY --from=build-stage /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 1. Script entry - no se si funcionara en el server
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
# 2. Le damos permisos de ejecución
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && \
    chmod +x /usr/local/bin/entrypoint.sh
# 3. Definicion del ENTRYPOINT
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
# 4. El puerto y el comando final
EXPOSE 9000
CMD ["php-fpm"]
