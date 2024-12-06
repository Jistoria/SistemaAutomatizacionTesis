<?php

namespace Modules\Auth\Services;

use App\Models\Auth\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log as FacadesLog;
use Modules\Auth\Contracts\AuthServiceInterface;
use Modules\Auth\Events\authEvent;

/**
 * Servicio de autenticación para manejar login, logout y obtener detalles del usuario.
 */
class AuthService implements AuthServiceInterface
{
    /**
     * Instancia del modelo User.
     *
     * @param User $user Modelo User que será utilizado para consultar la base de datos.
     */
    public function __construct(
        protected User $user
    )
    {}

    /**
     * Autenticar al usuario con credenciales de correo electrónico y contraseña.
     *
     * @param array $credentials Array asociativo que contiene 'email' y 'password' del usuario.
     * @return bool|array Retorna false si las credenciales no son válidas o un array con el token y el usuario si el login es exitoso.
     */
    public function login(array $credentials) : bool|array
    {
        // Busca al usuario por email.
        $user = $this->user->where('email', $credentials['email'])->first();

        // Verifica si el usuario existe y si la contraseña es válida.
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        // Crea un token de acceso para el usuario autenticado.
        $token = $user->createToken('auth_token')->accessToken;

        // Dispara un evento indicando que el usuario ha iniciado sesión.
        $this->authEvent($user->name, 'Usuario logueado');

        // Retorna un array con el token y los datos del usuario.
        return ['token' => $token, 'user' => $this->setUser($user)];
    }

    /**
     * Cierra la sesión del usuario eliminando el token de acceso actual.
     *
     * @param User $user Instancia del modelo User que tiene el token actual.
     * @return void
     */
    public function logout(User $user) : void
    {
       // Elimina el token actual del usuario, invalidando el acceso.
        $user->token()->revoke();
    }

    /**
     * Dispara un evento de autenticación para registrar acciones de los usuarios.
     *
     * @param string $name Nombre del usuario que realiza la acción.
     * @param string $message Mensaje que describe la acción realizada.
     * @return void
     */
    public function authEvent(string $name, string $message) : void
    {
        // Dispara un evento de autenticación para el usuario.
        event(new authEvent($name, $message));
    }

    /**
     * Obtiene los detalles de sesión del usuario.
     *
     * @param User $user Instancia del modelo User que contiene los datos del usuario autenticado.
     * @return array Retorna un array con los detalles de la sesión del usuario.
     */
    public function setUser(User $user) : array
    {
        // Llama a la función setSessionDetails del modelo User y retorna los datos filtrados.
        return $user->setSessionDetails();
    }

    /**
     * Verifica si el usuario tiene un token de acceso válido.
     *
     * @param User $user Instancia del modelo User que contiene el token de acceso.
     * @return bool Retorna true si el token es válido, de lo contrario retorna false.
     */
    public function checkToken(User $user) : bool
    {
        // Verifica si el token del usuario es válido.
        return $user->token()->exists();
    }

    public function allUsers()
    {
        FacadesLog::info('AuthService: allUsers');
        FacadesLog::info($this->user);
        return $this->user->all();
    }

    public function loginMS($credentials) : bool|array
    {
        // Validar el token de Microsoft
        $decoded = $this->validateMicrosoftToken($credentials['jwt']);


        // Verificar si el token es válido
        if (!$decoded) {
            return false;
        }

        // Buscar al usuario por email
        $user = $this->user->where('email', $decoded['email'])
            ->orWhere('name', $decoded['name'])
        ->first();

        // // Crear un nuevo usuario si no existe
        if (!$user) {
            throw new \Exception('Usuario no encontrado', 404);
        }

        // Crear un token de acceso para el usuario autenticado
        $token = $user->createToken('auth_token')->accessToken;

        // Disparar un evento indicando que el usuario ha iniciado sesión
        $this->authEvent($user->name, 'Usuario logueado');

        // Retornar un array con el token y los datos del usuario
        return ['token' => $token, 'user' => $this->setUser($user)];

    }

    protected function validateMicrosoftToken($jwt)
    {
        try {
            // URL del endpoint para obtener las claves públicas de Microsoft
            $jwkUrl = 'https://login.microsoftonline.com/common/discovery/v2.0/keys';

            // Descargar las claves públicas
            $json = file_get_contents($jwkUrl);
            $keys = json_decode($json, true)['keys'];
            foreach ($keys as &$key) {
                if (!isset($key['alg'])) {
                    $key['alg'] = 'RS256'; // Asegúrate de usar el algoritmo correcto
                }
            }

            // Convertir las claves públicas al formato que usa firebase/php-jwt
            $jwks = JWK::parseKeySet(['keys' => $keys]);


            // Decodificar y validar el token
            $decoded = JWT::decode($jwt, $jwks);

            // Verificar claims adicionales (opcional)
            // if ($decoded->aud !== 'tu-client-id') { // Cambia 'tu-client-id' por tu Client ID de Microsoft
            //     throw new \Exception('Invalid audience');
            // }

            // if ($decoded->iss !== 'https://login.microsoftonline.com/{tenant_id}/v2.0') { // Reemplaza {tenant_id}
            //     throw new \Exception('Invalid issuer');
            // }

            return (array) $decoded; // Devolver el token decodificado como array

        } catch (\Exception $e) {
            // Error durante la validación
            dd($e->getMessage());
            return null;
        }
    }
}
