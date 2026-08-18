#!/bin/bash

echo "=== INICIANDO ENTRYPOINT ==="
php artisan storage:link
# 1. Agregar hostname de Aiven al /etc/hosts
HOSTNAME="mysql-3dc5b7be-base-de-datos-proyectos.g.aivencloud.com"
echo "Intentando resolver $HOSTNAME..."

if [ ! -z "$DB_HOST_IP" ]; then
    echo "Usando IP desde variable DB_HOST_IP: $DB_HOST_IP"
    echo "$DB_HOST_IP $HOSTNAME" >> /etc/hosts
fi

# 2. Crear .env
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
fi

# 3. ¡GENERAR APP_KEY - AHORA SÍ!
echo "Generando APP_KEY..."
php artisan key:generate --force --no-interaction

# 4. Verificar que se generó correctamente
if grep -q "APP_KEY=" /var/www/html/.env; then
    echo "APP_KEY generada correctamente"
else
    echo "Generando APP_KEY manualmente..."
    echo "APP_KEY=base64:$(openssl rand -base64 32)" >> /var/www/html/.env
fi

# 5. Limpiar y cachear
echo "Limpiando caché..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# 7. Iniciar Apache
echo "Iniciando Apache..."
apache2-foreground