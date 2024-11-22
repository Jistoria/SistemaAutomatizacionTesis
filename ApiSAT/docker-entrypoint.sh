#!/bin/bash
set -e

# Ejecutar migraciones
php artisan migrate --force

# Inicializar supervisord
exec "$@"
