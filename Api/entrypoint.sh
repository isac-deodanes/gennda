#!/bin/bash

# Generamos la clave de la aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    # Si no existe .env, creamos uno temporal
    if [ ! -f /var/www/html/.env ]; then
        echo "Creando archivo .env temporal..."
        touch /var/www/html/.env
        echo "APP_ENV=${APP_ENV:-production}" >> /var/www/html/.env
        echo "APP_DEBUG=${APP_DEBUG:-false}" >> /var/www/html/.env
        echo "APP_URL=${APP_URL}" >> /var/www/html/.env
        echo "DB_CONNECTION=${DB_CONNECTION}" >> /var/www/html/.env
        echo "DB_HOST=${DB_HOST}" >> /var/www/html/.env
        echo "DB_PORT=${DB_PORT}" >> /var/www/html/.env
        echo "DB_DATABASE=${DB_DATABASE}" >> /var/www/html/.env
        echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/html/.env
        echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/html/.env
    fi
    php artisan key:generate --force
fi

# Limpiamos y cacheamos la configuración para producción
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutamos migraciones (si existen)
php artisan migrate --force

# Iniciamos Apache en primer plano
apache2-foreground