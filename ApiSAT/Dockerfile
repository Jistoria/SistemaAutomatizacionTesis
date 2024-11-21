FROM php:8.2-fpm


# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos de la aplicación
COPY ./ApiSAT /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Configurar permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto
EXPOSE 8000

# Ejecutar migraciones
RUN php artisan migrate

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=8000
