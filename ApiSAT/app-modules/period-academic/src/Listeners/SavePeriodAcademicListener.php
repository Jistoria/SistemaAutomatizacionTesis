<?php

namespace Modules\PeriodAcademic\Listeners;

use App\Utils\DateUtils;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\ImportDataFile\Events\ProcessThesisDataGenerated as EventsProcessThesisDataGenerated;
use Modules\PeriodAcademic\Services\PeriodAcademicService;
use ProcessThesisDataGenerated;

class SavePeriodAcademicListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected PeriodAcademicService $periodAcademicService
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventsProcessThesisDataGenerated $event): void
    {
        $periodAcademic = $this->extractData($event->studentsData);
        $this->periodAcademicService->createPeriodAcademic([
            'name' => $periodAcademic['name'],
            'start_date' => $periodAcademic['start_date'],
            'end_date' => $periodAcademic['end_date'],
            'created_by' => $event->userId,
            'updated_by' => $event->userId,
        ]);
    }

    public function extractData(array $data): array
    {
        $periodAcademicName = $data['period_academic'] ?? 'Periodo Desconocido';
        $startDateString = DateUtils::convertMonthToEnglish($data['start_date']);
        $endDateString = DateUtils::convertMonthToEnglish($data['end_date']);

        $startDate = preg_replace('/^[a-z]+, /i', '', $startDateString); // Eliminar día de la semana
        $startDate = preg_replace('/\sde\s/i', ' ', $startDate); // Eliminar la palabra "de"

        $endDate = preg_replace('/^[a-z]+, /i', '', $endDateString); // Eliminar día de la semana
        $endDate = preg_replace('/\sde\s/i', ' ', $endDate); // Eliminar la palabra "de"
        // Convierte las fechas usando el formato "d F Y"
        $formattedStartDate = Carbon::createFromFormat('d F Y', $startDate)->toDateString();
        $formattedEndDate = Carbon::createFromFormat('d F Y', $endDate)->toDateString();
        return [
            'name' => $periodAcademicName,
            'start_date' => $formattedStartDate,
            'end_date' => $formattedEndDate,
        ];
    }
}
