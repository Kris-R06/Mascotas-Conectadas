#!/bin/bash

# 1. Crear directorios esenciales de Laravel si no existen
mkdir -p /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/framework/cache \
         /var/www/storage/app/public \
         /var/www/storage/logs \
         /var/www/bootstrap/cache

# 2. Otorgar permisos globales de lectura y escritura a las carpetas de almacenamiento
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# 3. Iniciar PHP-FPM en segundo plano
php-fpm &
sleep 2

# 4. Generar la clave de la aplicación si no está definida en Render
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY no detectada. Generando clave automática..."
    php artisan key:generate --force
fi

# 5. Ejecutar migraciones y seeders de forma segura
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "Ejecutando seeders de datos iniciales..."
php artisan db:seed --force || true

# 6. Recrear el enlace simbólico de imágenes
rm -rf /var/www/public/storage
php artisan storage:link || true

# 7. Limpiar y recachear configuración, rutas y vistas
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Re-asegurar los permisos para el usuario www-data (Nginx / PHP-FPM)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# 9. Iniciar Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g "daemon off;"