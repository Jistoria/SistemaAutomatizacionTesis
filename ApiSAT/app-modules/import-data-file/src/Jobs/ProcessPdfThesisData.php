<?php

namespace Modules\ImportDataFile\Jobs;

use App\Models\Academic\Student\Student;
use App\Models\Academic\Teacher\Teacher;
use App\Models\Academic\Teacher\ThesisCommittee;
use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Academic\Thesis\ThesisTitle;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Degree\Contracts\DegreeServiceInterface;
use Modules\ImportDataFile\Events\NotificationDataProcess;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\Thesis\Contracts\ThesisTitleServiceInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Modules\ImportDataFile\DataTransferObjects\PdfThesisData;
use Modules\User\Contracts\UserServiceInterface;

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
        protected string $id,
    )
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
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

        // Extraer las fechas de inicio y fin del periodo académico
        $data = new PdfThesisData($studentsData);

        DB::transaction(function () use ($studentsData, $data) {

            // Crear o encontrar el periodo académico
            $periodAcademic = app(PeriodAcademicServiceInterface::class)->createPeriodAcademic((array) $data->getPeriodAcademic());
            // Crear o encontrar la carrera (Degree)
            $degree = app(DegreeServiceInterface::class)->createDegree(['name' => $studentsData['degree'], 'created_by' => $this->id, 'updated_by' => $this->id]);

            // Guardar los datos de estudiantes y sus relaciones
            foreach ($studentsData['students'] as $studentData) {
                // Crear o encontrar el profesor (Teacher)
                $roleDocente = Role::firstOrCreate(['name' => 'Docente-tesis']);
                $teacher = app(UserServiceInterface::class)->createUserWithRole($studentData['teacher'], 'Docente-tesis');

                $teacher->assignRole($roleDocente);

                // Crear o encontrar la tesis (ThesisTitle)
                $thesis = app(ThesisTitleServiceInterface::class)->createThesisTitle([
                    'title' => $data->getThesisTitle()
                ]);

                // Crear el usuario del estudiante si no existe
                $roleEstudiante = Role::firstOrCreate(['name' => 'Estudiante-tesis']);
                $user = User::firstOrCreate(
                    ['name' => $studentData['student_name']],
                    [
                        'name' => $studentData['student_name'],
                        'email' => 'e' . strtolower(str_replace(' ', '.', $studentData['student_dni'])) . '@uleam.edu.ec',
                        'password' => $studentData['student_dni'],
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
                ThesisProcess::firstOrCreate(
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
                        'date_start' => $data->dataDates['start_date'],
                        'date_end' => $data->dataDates['end_date'],
                        'created_by_user' => $this->id,
                        'updated_by_user' => $this->id
                    ]
                );
            }
        });
        event(new NotificationDataProcess(
            message: 'Proceso completado correctamente',
            status: 'success',
            name_document: basename($this->filePath),
            id: $this->id
        ));
    } catch (\Exception $e) {
        // Emitir un evento en caso de error en el proceso
        event(new NotificationDataProcess(
            message: 'Error en el procesamiento de datos, verifique el archivo PDF',
            status: 'error',
            name_document: basename($this->filePath),
            id: $this->id
        ));
    }
    }




}
