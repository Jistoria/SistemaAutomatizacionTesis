<?php

namespace Modules\ImportDataFile\Jobs;

use App\Models\Academic\Teacher\Teacher;
use App\Utils\State;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as FacadesLog;
use Modules\Degree\Contracts\DegreeServiceInterface;
use Modules\ImportDataFile\Events\NotificationDataProcess;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\Thesis\Contracts\ThesisTitleServiceInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Modules\ImportDataFile\DataTransferObjects\PdfThesisData;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;
use Modules\Thesis\Contracts\ThesisProcessServiceInterface;
use Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface;
use Modules\User\Contracts\StudentServiceInterface;
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
            $exePy  = env('APP_ENV') === 'production' ? 'python3' : 'py';


            $process = new Process([$exePy, base_path('ScriptsPython/formaterTableTesisPDF.py'), $this->filePath]);
            $process->run();

            // Si el proceso falla
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Obtener la salida del script de Python y convertirla a UTF-8
            $output = $process->getOutput();
            $output = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $output); // Convertir a UTF-8 si el JSON está en ISO-8859-1
            $studentsData = json_decode($output, true);


            // Extraer las fechas de inicio y fin del periodo académico
            $data = new PdfThesisData($studentsData);

            $periodAcademic = app(PeriodAcademicServiceInterface::class)
                                ->createPeriodAcademic((array) $data->getPeriodAcademic(), $this->id);
            // Crear o encontrar la carrera (Degree)
            $degree = app(DegreeServiceInterface::class)
                    ->createDegree(['name' => $studentsData['degree'], 'created_by' => $this->id, 'updated_by' => $this->id]);

            // // Guardar los datos de estudiantes y sus relaciones
            foreach ($studentsData['students'] as $studentData) {

                $teacher = app(UserServiceInterface::class)->createUserWithRole([
                    'name' => $studentData['tutor_name'],
                    'email' => 'd' . strtolower(
                                    preg_replace(
                                        '/[^a-zA-Z0-9]/', // Expresión regular para eliminar caracteres especiales
                                        '.',              // Reemplaza caracteres no válidos con un punto
                                        str_replace(
                                            ' ', '.',      // Reemplaza espacios por puntos
                                            substr($studentData['tutor_name'], 0, 4) // Toma los primeros 4 caracteres del nombre del tutor
                                        )
                                    )
                                ) . '@uleam.edu.ec',
                    'password' => 'DocUleamFCVT',
                ], 'Docente-tesis', $this->id);

                $te = Teacher::firstOrCreate([
                    'teacher_id' => $teacher->id,
                ]);

                $categoryIds = $te->categoryAreas->pluck('category_area_id')->take(2)->toArray();

                // Crear o encontrar la tesis (ThesisTitle)
                $thesis = app(ThesisTitleServiceInterface::class)->createThesisTitle([
                    'title' => $studentData['thesis_title'],
                ], $categoryIds);


                $user = app(UserServiceInterface::class)->createUserWithRole([
                    'name' => $studentData['student_name'],
                    'email' => 'e' . strtolower(str_replace(' ', '.', $studentData['student_dni'])) . '@uleam.edu.ec',
                    'password' => $studentData['student_dni'],
                ], 'Estudiante-tesis', $this->id);


                // Crear o encontrar el estudiante (Student) y asociar tesis y profesor
                app(StudentServiceInterface::class)->firstOrCreateStudent([
                    'dni' => $studentData['student_dni'],
                    'student_id' => $user->id,
                    'email' => 'e' . strtolower(str_replace(' ', '.', $studentData['student_dni'])) . '@uleam.edu.ec',
                    'name' => $studentData['student_name'],
                    'thesis_id' => $thesis->thesis_id,
                    'degree_id' => $degree->degree_id,
                    'enrollment_date' => now(),
                ], $user->id);

                // Crear la relación tutor-estudiante-tesis-periodo académico
                $thesis_process = app(ThesisProcessServiceInterface::class)->firstOrCreateThesisProcess([
                    'teacher_id' => $teacher->id,
                    'student_id' => $user->id,
                    'thesis_id' => $thesis->thesis_id,
                    'period_academic_id' => $periodAcademic->period_academic_id,
                    'date_start' => $periodAcademic->start_date,
                    'date_end' => $periodAcademic->end_date
                ], $this->id);

                $phase = app(ThesisPhasesServiceInterface::class);
                $phase_1 = $phase->getThesisPhaseByOrder(1,1);
                $thesis_process_phase_student = app(ThesisProcessStudentServiceInterface::class);

                $phase_student = $thesis_process_phase_student->registerThesisProcessPhase([
                    'thesis_process_id' => $thesis_process->thesis_process_id,
                    'thesis_phases_id' => $phase_1->thesis_phases_id,
                    'approval' => false,
                    'state_now' => State::IN_PROCESS,
                    'teacher_id' => $teacher->id,
                    'student_id' => $user->id,
                    'thesis_id' => $thesis->thesis_id,
                    'date_start' => $periodAcademic->date_start,
                    'period_academic_id' => $periodAcademic->period_academic_id,
                ], $this->id);

                $thesis_process_phase_student->aprovedThesisProcessPhase($phase_student->thesis_process_phases_id, $this->id);

                $phase_2 = $phase->getThesisPhaseByOrder(1,2);

                $thesis_process_phase_student->registerThesisProcessPhase(
                    [
                    'thesis_process_id' => $thesis_process->thesis_process_id,
                    'thesis_phases_id' => $phase_2->thesis_phases_id,
                    'approval' => false,
                    'state_now' => State::IN_PROCESS,
                    'teacher_id' => $teacher->id,
                    'student_id' => $user->id,
                    'thesis_id' => $thesis->thesis_id,
                    'period_academic_id' => $periodAcademic->period_academic_id,
                    ], $this->id
                );


            }

        event(new NotificationDataProcess(
            message: 'Proceso completado correctamente',
            status: 'success',
            name_document: basename($this->filePath),
            error: '',
            id: $this->id
        ));
    } catch (\Exception $e) {
        FacadesLog::error($e->getMessage());
        event(new NotificationDataProcess(
            message: 'Error en el procesamiento de datos, verifique el archivo PDF',
            status: 'error',
            name_document: basename($this->filePath),
            error: $e->getMessage(),
            id: $this->id
        ));
    }
    }




}
