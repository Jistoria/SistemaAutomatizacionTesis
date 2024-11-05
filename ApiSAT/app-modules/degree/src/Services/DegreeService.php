<?php

namespace Modules\Degree\Services;

use App\Models\Academic\Degree;

class DegreeService
{
    public function __construct(
        protected Degree $degree
    )
    {}

    /**
     * Crea un carrera académica.
     *
     * @param array $data Los datos del grado académico.
     * @return Degree El carrera académica creado/encontrada.
     */
    public function createDegree(array $data): Degree
    {
        return $this->degree->firstOrCreate(['name' => $data['name']], $data);
    }
}
