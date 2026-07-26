# FASE 1: BUILD FRONTEND
FROM node:20 AS node_builder
WORKDIR /app 
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# FASE 2: BUILD BACKEND
FROM php:8.4-fpm
# Instalar depedencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd
# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Crear directorio app
WORKDIR /var/www
# Copiar proyecto
COPY . .
# Copiar assets compilados del frontend
COPY --from=node_builder /app/public/build /var/www/public/build
# Instalar dependecias de laravel
RUN composer install --no-dev --optimize-autoloader
# Permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# copiar configuración de nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
# Script de arranque
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 10000
CMD [ "/entrypoint.sh" ]