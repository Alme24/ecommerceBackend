# 1. Imagen base PHP 8.2 con FPM
FROM php:8.2-fpm

# 2. Instalar dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Establecer directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar todos los archivos del proyecto
COPY . .

# 6. Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# 7. Dar permisos correctos a storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 8. Exponer el puerto 9000 (PHP-FPM)
EXPOSE 9000

# 9. Comando por defecto
CMD ["php-fpm"]

