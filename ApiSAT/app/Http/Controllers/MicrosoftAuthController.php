<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Auth\Contracts\AuthServiceInterface;

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


    public function authenticatedMS(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'jwt' => 'required|string',
        ]);
        try {
                $user = app(AuthServiceInterface::class)->loginMS($request->all());
                if (!$user) {
                    return ApiResponse::error('Usuario no autorizado', 401);
                }
                return ApiResponse::success($user, 'Usuario autenticado correctamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }
    }
}

