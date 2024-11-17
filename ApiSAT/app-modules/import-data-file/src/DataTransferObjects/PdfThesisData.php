<?php
namespace Modules\ImportDataFile\DataTransferObjects;

use App\Utils\DateUtils;
use Illuminate\Support\Facades\Log;

class PdfThesisData
{
    protected array $data;
    public array $dataDates;

    public function __construct(array $data)
    {
        Log::info('PdfThesisData: ' . json_encode($data));
        $this->data = $data;
        $this->dataDates = $this->extractDataDate($data);
    }

    // Método para obtener el período académico
    public function getPeriodAcademic(): array
    {
        $periodAcademic = [
            'name' => $this->data['period_academic'],
            'start_date' => $this->dataDates['start_date'],
            'end_date' => $this->dataDates['end_date'],
        ];
        return $periodAcademic;
    }

    // Método para obtener la fecha de inicio formateada
    public function getStartDate(): string
    {
        return $this->formatDate($this->data['start_date']);
    }

    // Método para obtener la fecha de fin formateada
    public function getEndDate(): string
    {
        return $this->formatDate($this->data['end_date']);
    }

    // Método para obtener el grado o carrera
    public function getDegree(): string
    {
        return $this->data['degree'] ?? 'Grado Desconocido';
    }

    // Método para obtener los datos de estudiantes
    public function getStudents(): array
    {
        return $this->data['students'] ?? [];
    }

    public function getThesisTitle(): string
    {
        return $this->data['thesis_title'] ?? 'Título de tesis desconocido';
    }

    // Método privado para formatear fechas
    protected function formatDate(string $date): string
    {
        // Lógica para formatear la fecha según sea necesario
        return \Carbon\Carbon::createFromFormat('d F Y', $date)->toDateString();
    }

    protected function extractDataDate(array $data): array{
        $startDateString = DateUtils::convertMonthToEnglish($data['start_date']);
        $endDateString = DateUtils::convertMonthToEnglish($data['end_date']);

        $startDate = preg_replace('/^\pL+, /u', '', $startDateString); // Eliminar día de la semana
        $startDate = preg_replace('/\sde\s/i', ' ', $startDate); // Eliminar la palabra "de"

        $endDate = preg_replace('/^\pL+, /u', '', $endDateString); // Eliminar día de la semana
        $endDate = preg_replace('/\sde\s/i', ' ', $endDate); // Eliminar la palabra "de"
        // Convierte las fechas usando el formato "d F Y"
        $formattedStartDate = \Carbon\Carbon::createFromFormat('d F Y', $startDate)->toDateString();
        $formattedEndDate = \Carbon\Carbon::createFromFormat('d F Y', $endDate)->toDateString();
        return [
            'start_date' => $formattedStartDate,
            'end_date' => $formattedEndDate,
        ];
    }


}
