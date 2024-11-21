# Usa una imagen oficial de PHP con FPM
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

# Establecer el directorio de trabajo
WORKDIR /var/www

# Configurar permisos y optimizar el autoloader de Composer
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && composer install --no-dev --optimize-autoloader

# Establecer entorno de producción para Laravel
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
