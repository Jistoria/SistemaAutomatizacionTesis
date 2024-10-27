<?php

namespace Modules\ImportDataFile\Jobs;

use App\Models\Academic\Student\Student;
use App\Models\Academic\Teacher\Teacher;
use App\Models\Academic\Thesis\ThesisTitle;
use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ProcessPdfThesisData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, protected $id)
    {
        $this->filePath = $filePath;
    }

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
        $output = $process->getOutput();
        $studentsData = json_decode($output, true);

        // Guardar los datos en la base de datos
        foreach ($studentsData as $studentData) {
            // Crear o encontrar el profesor (Teacher)
            $teacher = Teacher::firstOrCreate(
                ['name' => $studentData['tutor_name']],
                ['created_by_user' => $this->id, 'updated_by_user' => $this->id]
            );

            // Crear o encontrar la tesis (ThesisTitle)
            $thesis = ThesisTitle::firstOrCreate(
                ['title' => $studentData['thesis_title']],
                ['created_by_user' => $this->id, 'updated_by_user' => $this->id]
            );

            // Crear el usuario del estudiante si no existe
            $user = User::firstOrCreate(
                [
                    'name' => $studentData['student_name'],
                    'email' => strtolower(str_replace(' ', '.', $studentData['student_name'])) . '@example.com',
                    'password' => bcrypt('password'),
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );

            // Crear o encontrar el estudiante (Student) y asociar tesis y profesor
            Student::firstOrCreate(
                ['student_id' => $user->id],
                [
                    'dni' => $studentData['student_dni'],
                    'thesis_id' => $thesis->id,
                    'enrollment_date' => now(),
                    'created_by_user' => $this->id,
                    'updated_by_user' => $this->id
                ]
            );
        }
    }
}
