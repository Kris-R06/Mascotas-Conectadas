#!/bin/bash

# Iniciar PHP-FPM en segundo plano
php-fpm &

# Esperar un poco
sleep 3

# Generar la clave de la aplicación si no existe
#php artisan key:generate --force

# Migraciones 
php artisan migrate --force

# cachear configuración
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar nginx
nginx -g "daemon off;"