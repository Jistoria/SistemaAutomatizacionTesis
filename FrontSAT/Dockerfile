# Usa una imagen base de Node.js para instalar dependencias y construir el proyecto
FROM node:18-alpine AS builder

# Instalar dependencias del sistema
RUN apk add --no-cache python3 make g++ bash

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos necesarios para instalar las dependencias
COPY package*.json ./

# Instalar dependencias
RUN npm install

# Copiar el resto del proyecto
COPY . .

# Construir el proyecto Nuxt para producción
RUN npm run build

# -------------------
# Crear la imagen final con Nginx
# -------------------
FROM nginx:alpine

# Copiar la configuración personalizada de Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Eliminar configuración por defecto
RUN rm /etc/nginx/conf.d/default.conf

# Copiar los archivos generados por Nuxt al directorio que Nginx sirve
COPY --from=builder /app/.output/public /usr/share/nginx/html

# Copiar los archivos necesarios para SSR si aplica
COPY --from=builder /app/.output /usr/share/nginx/nuxt

# Configurar permisos
RUN chmod -R 775 /usr/share/nginx/html \
    && chown -R nginx:nginx /usr/share/nginx/html

# Exponer el puerto 80
EXPOSE 80

# Comando de inicio para Nginx
CMD ["nginx", "-g", "daemon off;"]