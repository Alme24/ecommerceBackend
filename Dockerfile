FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Dar permisos al storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
