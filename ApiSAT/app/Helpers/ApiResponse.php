<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
     /**
     * Validar si el código de estado es un código HTTP válido.
     *
     * @param int $status
     * @param int $default
     * @return int
     */
    private static function validateHttpStatus(int $status, int $default): int
    {
        $validStatuses = array_keys(Response::$statusTexts);
        return in_array($status, $validStatuses) ? $status : $default;
    }

    /**
     * Respuesta exitosa.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Operación exitosa', int $status = 200): JsonResponse
    {
        $status = self::validateHttpStatus($status, 200);
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Respuesta de error.
     *
     * @param string $message
     * @param int $status
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'Error en la operación', int $status = 500, $errors = null): JsonResponse
    {
        $status = self::validateHttpStatus($status, 500);
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
