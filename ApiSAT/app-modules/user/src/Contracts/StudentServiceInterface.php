<?php

namespace Modules\User\Contracts;

use App\Models\Academic\Student\Student;

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
}
