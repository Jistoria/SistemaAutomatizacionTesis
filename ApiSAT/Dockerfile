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

# Copiar los archivos del proyecto a la imagen
COPY . /var/www

# Crear el directorio de almacenamiento y sus subdirectorios necesarios
RUN mkdir -p /var/www/storage \
    /var/www/storage/app \
    /var/www/storage/framework \
    /var/www/storage/logs && \
    chown -R www-data:www-data /var/www/storage && \
    chmod -R 755 /var/www/storage

# Configurar permisos y optimizar el autoloader de Composer
RUN chown -R www-data:www-data /var/www && \
    composer install --no-dev --optimize-autoloader

# Configurar entorno de producción para Laravel
ENV APP_ENV=production \
    APP_DEBUG=false

# Generar clave de la aplicación y limpiar configuraciones
RUN php artisan key:generate \
    && php artisan config:clear \
    && php artisan config:cache

# Ejecutar migraciones como parte del build (opcional)
RUN php artisan migrate --force || echo "No se pudieron ejecutar las migraciones."

# Exponer el puerto del servidor
EXPOSE 80

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]

