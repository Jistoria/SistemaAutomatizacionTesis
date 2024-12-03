<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MicrosoftAuthController extends Controller
{
    public function getToken()
    {
        // Configuración de los parámetros para la autenticación
        $tenantId = env('AUTH_MS_TENANT'); // Reemplaza con tu Tenant ID
        $clientId = env('AUTH_MS_CLIENT_ID'); // Reemplaza con tu Client ID
        $clientSecret = env('AUTH_MS_CLIENT_SECRET'); // Reemplaza con tu Client Secret
        $scope = 'https://graph.microsoft.com/.default';

        // Endpoint de Microsoft para obtener el token
        $url = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        try {
            // Realizamos la solicitud al endpoint
            $response = Http::asForm()->post($url, [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => $scope,
            ]);

            // Verificamos la respuesta
            if ($response->ok()) {
                return response()->json($response->json(), 200); // Retornamos el token
            } else {
                return response()->json([
                    'error' => 'Error al obtener el token',
                    'details' => $response->json(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción al realizar la solicitud',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}

