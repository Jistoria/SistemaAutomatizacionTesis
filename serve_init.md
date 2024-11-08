# Iniciar el Proyecto Laravel

Para poner en marcha el proyecto Laravel, sigue los siguientes pasos.

## Instalación de Dependencias

Ejecuta el siguiente comando para instalar todas las dependencias del proyecto especificadas en el archivo `composer.json`:

```bash
composer install
```

## Configuración del Entorno

1. Copia el archivo `.env.example` y renómbralo como `.env`.
2. Configura las variables de entorno en el archivo `.env` según tu configuración local.

## Generar Clave de Aplicación

Genera una clave para la aplicación utilizando el siguiente comando:

```bash
php artisan key:generate
```

## Migración de Base de Datos

Ejecuta las migraciones para crear las tablas necesarias en la base de datos:

```bash
php artisan migrate
```

## Configuración de Passport

Genera las claves necesarias para Passport:

```bash
php artisan passport:keys
```

Luego, genera un cliente personal:

```bash
php artisan passport:client --personal
```

## Iniciar el Servidor Local

Para iniciar el entorno de desarrollo local, sigue estos pasos. Asegúrate de tener configurado tu archivo `.env` correctamente y de que todos los servicios necesarios estén en funcionamiento.

### Comandos de Inicio

1. **Iniciar el Servidor Laravel**

   ```bash
   php artisan serve
   ```

2. **Iniciar el Servidor WebSocket de Reverb**

   ```bash
   php artisan reverb:start --host=127.0.0.1 --port=8080
   ```

3. **Iniciar el Servidor de Queue**

   ```bash
   php artisan queue:work
   
