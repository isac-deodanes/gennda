#!/bin/bash

echo "=== INICIANDO ENTRYPOINT ==="

# 1. Agregar hostname de Aiven al /etc/hosts
HOSTNAME="mysql-3dc5b7be-base-de-datos-proyectos.g.aivencloud.com"
echo "Intentando resolver $HOSTNAME..."

# Si hay una variable DB_HOST_IP, usarla
if [ ! -z "$DB_HOST_IP" ]; then
    echo "Usando IP desde variable DB_HOST_IP: $DB_HOST_IP"
    echo "$DB_HOST_IP $HOSTNAME" >> /etc/hosts
else
    # Intentar resolver con getent
    IP=$(getent hosts $HOSTNAME | awk '{ print $1 }' 2>/dev/null)
    if [ ! -z "$IP" ] && [ "$IP" != " " ]; then
        echo "Resuelto: $HOSTNAME -> $IP"
        echo "$IP $HOSTNAME" >> /etc/hosts
    else
        echo "WARNING: No se pudo resolver $HOSTNAME"
        echo "Verifica que la variable DB_HOST en Render sea correcta"
    fi
fi

# 2. Crear .env si no existe
if [ ! -f /var/www/html/.env ]; then
    echo "Creando archivo .env..."
    echo "APP_ENV=${APP_ENV:-production}" > /var/www/html/.env
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> /var/www/html/.env
    echo "APP_URL=${APP_URL}" >> /var/www/html/.env
    echo "DB_CONNECTION=${DB_CONNECTION:-mysql}" >> /var/www/html/.env
    echo "DB_HOST=${DB_HOST}" >> /var/www/html/.env
    echo "DB_PORT=${DB_PORT:-3306}" >> /var/www/html/.env
    echo "DB_DATABASE=${DB_DATABASE}" >> /var/www/html/.env
    echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/html/.env
    echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/html/.env
else
    echo ".env ya existe, actualizando..."
    sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/g" /var/www/html/.env
    sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/g" /var/www/html/.env
fi

# 3. Generar APP_KEY
echo "Generando APP_KEY..."
php artisan key:generate --force --no-interaction

# 4. Limpiar y cachear
echo "Limpiando caché..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Migrar
echo "Ejecutando migraciones..."
php artisan migrate --force

# 6. Iniciar Apache
echo "Iniciando Apache..."
apache2-foreground