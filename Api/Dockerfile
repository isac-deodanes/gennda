# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalamos extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip curl

# Habilitamos mod_rewrite de Apache
RUN a2enmod rewrite

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuramos el DocumentRoot de Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configuramos el puerto que Render asignará
ENV PORT=10000
RUN echo "Listen ${PORT}" >> /etc/apache2/ports.conf
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
RUN sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Copiamos el código de la aplicación
COPY Api/ /var/www/html

# Instalamos las dependencias de PHP (sin dev para producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configuramos permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuramos la variable de entorno para que Laravel sepa que está en producción
ENV APP_ENV=production
ENV APP_DEBUG=false

# Exponemos el puerto 10000 (que Render usará)
EXPOSE 10000

# Script de inicio que ejecuta migraciones y optimizaciones
COPY /Api/entrypoint.sh /Api/entrypoint.sh
RUN chmod +x /Api/entrypoint.sh

CMD ["/Api/entrypoint.sh"]