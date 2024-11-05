<?php
namespace Modules\Degree\Contracts;

use App\Models\Academic\Degree;

interface DegreeServiceInterface
{
    /**
     * Crea un carrera académica.
     *
     * @param array $data Los datos del carrera académica.
     * @return Degree El grado académico creado.
     */
    public function createDegree(array $data): Degree;
}
