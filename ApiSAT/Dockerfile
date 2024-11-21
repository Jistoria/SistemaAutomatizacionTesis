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
    unzip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar los archivos de la aplicación
COPY . /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Configurar permisos
RUN mkdir -p /var/www/storage \
    && chown -R www-data:www-data /var/www/storage \
    && chmod -R 755 /var/www/storage

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Configurar Laravel y cachear configuración
RUN php artisan config:clear && php artisan config:cache

# Exponer el puerto
EXPOSE 80

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=80
