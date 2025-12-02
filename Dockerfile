FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Exponer puerto para Railway
EXPOSE 8080

# Comando final: generar key si no existe y arrancar el servidor
CMD ["/bin/sh", "-c", "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host 0.0.0.0 --port 8080"]
