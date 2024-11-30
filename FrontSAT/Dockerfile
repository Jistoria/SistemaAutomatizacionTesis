# Usa una imagen base con Node.js y Debian como base para instalar Nginx
FROM node:18-bullseye

# Actualizar e instalar Nginx y dependencias necesarias
RUN apt-get update && apt-get install -y nginx && apt-get clean

# Establecer directorio de trabajo
WORKDIR /app

# Copiar los archivos necesarios para construir la aplicación
COPY package*.json ./

# Instalar dependencias de Node.js
RUN npm install

# Copiar el resto de los archivos del proyecto
COPY . .

# Construir la aplicación Nuxt para producción
RUN npm run build

# Configurar Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

RUN rm /etc/nginx/sites-enabled/default

# Permisos para la carpeta de la aplicación
RUN chmod -R 775 /app && chown -R www-data:www-data /app

# Exponer los puertos
EXPOSE 80

# Iniciar el servidor Nuxt en segundo plano y Nginx como principal
CMD ["sh", "-c", "node .output/server/index.mjs & nginx -g 'daemon off;'"]
