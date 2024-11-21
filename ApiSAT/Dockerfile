FROM php:8.2-fpm


# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    zip \
    unzip

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos de la aplicación
COPY .. /var/www

RUN ls -la /var/www

RUN ls -la /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www

# Configurar permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader


# Ejecutar un comando Laravel para confirmar la conexión con la base de datos
RUN php artisan config:clear && php artisan config:cache
RUN php artisan migrate:status || echo "Problema al conectar con la base de datos."

# Ejecutar tu comando personalizado
RUN php artisan init:service-api || echo "Error al inicializar el servicio API."

# Exponer el puerto
EXPOSE 80

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=80
