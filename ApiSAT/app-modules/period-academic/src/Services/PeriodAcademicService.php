<?php

namespace Modules\PeriodAcademic\Services;

use App\Models\Academic\PeriodAcademic;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log as FacadesLog;
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
    public function createPeriodAcademic(array $data, string $userId): PeriodAcademic
    {
        $periodAcademic = $this->periodAcademic->firstOrCreate(['name' => $data['name']], [
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'created_by_user' => $userId,
            'updated_by_user' => $userId,
        ]);
        return $periodAcademic;
    }

    public function getNowPeriodAcademic(): PeriodAcademic
    {
        return $this->periodAcademic->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }



}
