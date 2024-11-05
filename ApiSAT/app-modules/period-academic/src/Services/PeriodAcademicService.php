<?php

namespace Modules\PeriodAcademic\Services;

use App\Models\Academic\PeriodAcademic;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;

class PeriodAcademicService implements PeriodAcademicServiceInterface
{
    public function __construct(
        protected PeriodAcademic $periodAcademic
    )
    {}

    /**
     * Crea un periodo académico.
     *
     * @param array $data Los datos del periodo académico.
     * @return PeriodAcademic El periodo académico creado/encontrado.
     */
    public function createPeriodAcademic(array $data): PeriodAcademic
    {
        return $this->periodAcademic->firstOrCreate(['name' => $data['name']], $data);
    }



}
