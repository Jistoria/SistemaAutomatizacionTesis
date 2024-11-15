<?php

namespace Modules\ThesisTutor\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ensureIsStudentTutor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = $request->user();
            $studentId = $request->route('student_id');

            // Validar si el student_id tiene formato de UUID
            $validator = Validator::make(['student_id' => $studentId], [
                'student_id' => 'uuid',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('El ID del estudiante no tiene un formato UUID válido', 400);
            }

            // Verificar la relación de tutor con el estudiante
            if (!$user->teacher->isTutorOf($studentId)) {
                return ApiResponse::error('No tienes permisos para realizar esta acción', 403);
            }

            return $next($request);

        } catch (ModelNotFoundException $e) {
            // Manejar el caso en que el estudiante no existe en la base de datos
            return ApiResponse::error('Estudiante no encontrado', 404);

        } catch (\Exception $e) {
            // Capturar cualquier otra excepción
            return ApiResponse::error('Error al validar permisos', 500);
        }
    }
}
