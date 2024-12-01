<?php

namespace Modules\User\Contracts;

use App\Models\Academic\Student\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface StudentServiceInterface
{
    /**
     * Crea o devuelve el primer estudiante que coincida con los datos proporcionados.
     *
     * @param array $data Datos necesarios para crear o buscar el estudiante.
     * @param string $userId ID del usuario que esta haciendo la creacion/consulta.
     * @return Student El estudiante creado o encontrado.
     */
    public function firstOrCreateStudent(array $data, string $userId): Student;

    /**
     * Obtiene un estudiante con sus relaciones.
     *
     * @param array|string $relations Relaciones a cargar con el estudiante.
     * @param string $studentId ID del estudiante a buscar.
     * @return Student Estudiante con sus relaciones.
     */
    public function getStudentWithRelations(array|string $relations, string $studentId): Student;

    /**
     * Obtiene una colección de estudiantes paginada con sus relaciones.
     *
     * @param array|string $relations Relaciones a cargar con los estudiantes.
     * @param int $pagination Número de resultados por página.
     * @return LengthAwarePaginator Colección de estudiantes paginada con sus relaciones.
     */
    public function getPaginatedStudentsWithRelations(array|string $relations, int $pagination): LengthAwarePaginator;


}
