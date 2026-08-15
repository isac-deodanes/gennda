#!/bin/bash

# Crear .env si no existe
if [ ! -f /var/www/html/.env ]; then
    echo "Creando archivo .env temporal..."
    echo "APP_ENV=${APP_ENV:-production}" > /var/www/html/.env
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> /var/www/html/.env
    echo "APP_URL=${APP_URL}" >> /var/www/html/.env
    echo "DB_CONNECTION=${DB_CONNECTION:-mysql}" >> /var/www/html/.env
    echo "DB_HOST=${DB_HOST}" >> /var/www/html/.env
    echo "DB_PORT=${DB_PORT}" >> /var/www/html/.env
    echo "DB_DATABASE=${DB_DATABASE}" >> /var/www/html/.env
    echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/html/.env
    echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/html/.env
fi

# GENERAR APP_KEY - ¡FORZAR LA GENERACIÓN!
echo "Generando APP_KEY..."
php artisan key:generate --force --no-interaction

# Verificar que se generó correctamente
if [ -z "$(grep APP_KEY /var/www/html/.env)" ]; then
    echo "ERROR: No se pudo generar APP_KEY"
    echo "APP_KEY=base64:$(openssl rand -base64 32)" >> /var/www/html/.env
fi

# Limpiar y cachear
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar Apache
apache2-foreground