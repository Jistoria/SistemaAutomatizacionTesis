<?php

namespace Modules\ThesisTutor\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
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
        $user = $request->user();
        $studentId = $request->route('student_id');

        if (!$user->teacher->isTutorOf($studentId)) {
            return ApiResponse::error('No tienes permisos para realizar esta acción', 403);
        }

        return $next($request);
    }
}
