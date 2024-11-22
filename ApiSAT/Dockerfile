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
    nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar la extensión de Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instalar la extensión de Composer
RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
    pcntl

# Instalar supervisor
RUN apt-get update && apt-get install -y supervisor




# Instalar pdfplumber con pip3 usando --break-system-packages
RUN pip3 install pdfplumber --break-system-packages

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar los archivos del proyecto al contenedor
COPY .. /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Limpiar los logs
RUN truncate -s 0 /var/www/storage/logs/laravel.log


# Configurar permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache


# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Copiar configuración de Nginx
COPY ./default.conf /etc/nginx/conf.d/default.conf

RUN rm /etc/nginx/sites-enabled/default

COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Exponer los puertos necesarios
EXPOSE 80
EXPOSE 8080


# Comando de inicio
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
