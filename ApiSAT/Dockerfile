FROM php:8.2-fpm

# Instalar dependencias del sistema, incluyendo las necesarias para Python y pdfplumber
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    zip \
    unzip \
    python3 \
    python3-pip \
    libjpeg-dev \
    zlib1g-dev \
    libfreetype6-dev \
    liblcms2-dev \
    libwebp-dev \
    tcl-dev \
    tk-dev \
    python3-dev \
    build-essential \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar pdfplumber con pip3 usando --break-system-packages
RUN pip3 install pdfplumber --break-system-packages


# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar los archivos del proyecto al contenedor
COPY .. /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Configurar permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto
EXPOSE 80

# Comando de inicio
CMD ["sh", "-c", "php artisan config:clear && php artisan config:cache && php artisan init:service-api && php artisan serve --host=0.0.0.0 --port=80"]

