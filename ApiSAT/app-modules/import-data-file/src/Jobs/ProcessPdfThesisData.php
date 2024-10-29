<?php

namespace Modules\ImportDataFile\Jobs;

use App\Models\Academic\Degree;
use App\Models\Academic\PeriodAcademic;
use App\Models\Academic\Student\Student;
use App\Models\Academic\Teacher\Teacher;
use App\Models\Academic\Teacher\ThesisCommittee;
use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Academic\Thesis\ThesisTitle;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Carbon\Carbon;
use Faker\Core\Uuid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as FacadesLog;
use Modules\ImportDataFile\Utils\DateUtils;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ProcessPdfThesisData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct
    (
        protected string $filePath,
        protected string $id)
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Ejecutar el script de Python para procesar el archivo PDF
        $process = new Process(['py', base_path('ScriptsPython/formaterTableTesisPDF.py'), $this->filePath]);
        $process->run();

        // Si el proceso falla
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Obtener la salida del script de Python (datos extraídos en formato JSON)
        // Obtener la salida del script de Python y convertirla a UTF-8
        $output = $process->getOutput();
        $output = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $output); // Convertir a UTF-8 si el JSON está en ISO-8859-1
        $studentsData = json_decode($output, true);


        // Extraer los datos del periodo académico
        $periodAcademicName = $studentsData['period_academic'] ?? 'Periodo Desconocido';
        $startDateString = DateUtils::convertMonthToEnglish($studentsData['start_date']);
        $endDateString = DateUtils::convertMonthToEnglish($studentsData['end_date']);

        FacadesLog::info('Datos del periodo académico', [
            'periodAcademicName' => $periodAcademicName,
            'startDateString' => $startDateString,
            'endDateString' => $endDateString,
            'degree' => $studentsData['degree'],
        ]);

        $startDate = preg_replace('/^[a-z]+, /i', '', $startDateString); // Eliminar día de la semana
        $startDate = preg_replace('/\sde\s/i', ' ', $startDate); // Eliminar la palabra "de"

        $endDate = preg_replace('/^[a-z]+, /i', '', $endDateString); // Eliminar día de la semana
        $endDate = preg_replace('/\sde\s/i', ' ', $endDate); // Eliminar la palabra "de"

        // Convierte las fechas usando el formato "d F Y"
        $formattedStartDate = Carbon::createFromFormat('d F Y', $startDate)->toDateString();
        $formattedEndDate = Carbon::createFromFormat('d F Y', $endDate)->toDateString();
        // Crear o encontrar el periodo académico
        $periodAcademic = PeriodAcademic::firstOrCreate(
            ['name' => $periodAcademicName],
            [
                'name' => $periodAcademicName,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_by_user' => $this->id,
                'updated_by_user' => $this->id
            ]
        );
        $degree = Degree::firstOrCreate(
            ['name' => $studentsData['degree']],
            ['name' => $studentsData['degree'], 'created_by_user' => $this->id, 'updated_by_user' => $this->id]
        );

        // Guardar los datos en la base de datos
        foreach ($studentsData['students'] as $studentData) {
            // Crear o encontrar el profesor (Teacher)
            $roleDocente = Role::firstOrCreate(['name' => 'Docente-tesis']);
            $teacher = User::firstOrCreate(
                [
                    'name' => $studentData['tutor_name'],
                ],
                [
                    'name' => $studentData['tutor_name'],
                    'email' => strtolower(str_replace(' ', '.', $studentData['tutor_name'])) . '@uleam.edu.ec',
                    'password' => 'process_thesis',
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );
            $teacher->assignRole($roleDocente);
            // Crear o encontrar la tesis (ThesisTitle)
            $thesis = ThesisTitle::firstOrCreate(
                ['title' => $studentData['thesis_title']],
                ['created_by_user' => $this->id, 'updated_by_user' => $this->id]
            );

            // Crear el usuario del estudiante si no existe
            $roleEstudiante = Role::firstOrCreate(['name' => 'Estudiante-tesis']);
            $user = User::firstOrCreate(
                ['name' => $studentData['student_name']],
                [
                    'name' => $studentData['student_name'],
                    'email' => 'e'.strtolower(str_replace(' ', '.', $studentData['student_dni'])) . '@uleam.edu.ec',
                    'password' => $studentData['student_dni'],
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );
            $user->assignRole($roleEstudiante);

            // Crear o encontrar el estudiante (Student) y asociar tesis y profesor
            Student::firstOrCreate(
                ['student_id' => $user->id, 'dni' => $studentData['student_dni']],
                [
                    'student_id' => $user->id,
                    'dni' => $studentData['student_dni'],
                    'thesis_id' => $thesis->thesis_id,
                    'degree_id' => $degree->degree_id,
                    'enrollment_date' => now(),
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );

            // Crear la relación tutor-estudiante-tesis-periodo académico
            $thesisProcess = ThesisProcess::firstOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'student_id' => $user->id,
                    'thesis_id' => $thesis->thesis_id,
                    'period_academic_id' => $periodAcademic->period_academic_id,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'student_id' => $user->id,
                    'thesis_id' => $thesis->thesis_id,
                    'period_academic_id' => $periodAcademic->period_academic_id,
                    'state_now' => 'En proceso',
                    'date_start' => $formattedStartDate,
                    'date_end' => $formattedEndDate,
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );
        }
    }


}
