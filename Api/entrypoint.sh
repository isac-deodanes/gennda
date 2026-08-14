#!/bin/bash

# Generamos la clave de la aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
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