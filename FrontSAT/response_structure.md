# API Documentation

## HTTP Status Codes

La API devuelve los siguientes códigos de estado HTTP para indicar el resultado de la solicitud:

- **200 OK**: La solicitud fue exitosa.
- **201 Created**: Un recurso fue creado exitosamente.
- **400 Bad Request**: La solicitud es inválida (por ejemplo, parámetros faltantes o datos incorrectos).
- **401 Unauthorized**: La autenticación es requerida o ha fallado.
- **403 Forbidden**: El usuario no tiene permiso para acceder a este recurso.
- **404 Not Found**: El recurso solicitado no se pudo encontrar.
- **422 Unprocessable Entity**: Los datos enviados en la solicitud no pasan la validación.
- **500 Internal Server Error**: Ocurrió un error inesperado en el servidor.

## Estructura General de la Respuesta

Todas las respuestas de la API siguen la siguiente estructura:

```json
{
  "success": true,
  "message": "Operación exitosa",
  "error": "Error en la operación",
  "data": {
    // Aquí va el contenido específico de cada respuesta, por ejemplo, un objeto, lista o null
  }
}