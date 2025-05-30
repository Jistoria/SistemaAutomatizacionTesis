<?php
namespace Modules\PeriodAcademic\Contracts;

use App\Models\Academic\PeriodAcademic;

interface PeriodAcademicServiceInterface
{
    /**
     * Crea un periodo académico.
     *
     * @param array $data Los datos del periodo académico.
     * @return PeriodAcademic El periodo académico creado.
     */
    public function createPeriodAcademic(array $data, string $userId): PeriodAcademic;

    /**
     * Obtiene el periodo académico actual.
     *
     * @return PeriodAcademic El periodo académico actual.
     */
    public function getNowPeriodAcademic(): PeriodAcademic;
}
