<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Models\Academic\Teacher\Teacher;
use App\Models\Academic\Thesis\ThesisPhase;
use App\Utils\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;
use Modules\ThesisProcessStudent\Contracts\PreRequirementsStudentServiceInterface;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;
use Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface;
use Modules\ThesisProcessStudent\Models\ThesisProcessPhaseStudent;
use Modules\ThesisProcessStudent\Models\ThesisProcessStudent;

/**
 * Servicio para gestionar el proceso de tesis de los estudiantes.
 */
class ThesisProcessStudentService implements ThesisProcessStudentServiceInterface
{
    /**
     * Constructor del servicio.
     *
     * @param ThesisProcessPhaseStudent $thesisProcessPhases Instancia del modelo de fases del proceso de tesis.
     */
    public function __construct(
        protected ThesisProcessPhaseStudent $thesisProcessPhases,
        protected ThesisProcessStudent $thesisProcess,
        protected ThesisPhasesServiceInterface $thesisPhasesService,
        protected RequirementsStudentService $requirementsStudentService
    )
    {}

    /**
     * Obtiene el proceso de tesis de un estudiante por su ID.
     *
     * @param string $id ID del estudiante.
     * @return ThesisProcessStudent El proceso de tesis del estudiante.
     */

    public function findThesisProcessById(string $id)
    {
        return $this->thesisProcess->where('student_id', $id)->with(['tutor', 'tutor.user','tutor.categoryAreas', 'thesis', 'thesis.categoryAreas', 'periodAcademic'])->first();
    }


    /**
     * Registra una nueva fase del proceso de tesis.
     *
     * @param array $data Datos para crear la nueva fase del proceso de tesis.
     * @return ThesisProcessPhaseStudent La instancia creada de la fase del proceso de tesis.
     */
    public function registerThesisProcessPhase(array $data): ThesisProcessPhaseStudent|bool
    {

        if (!$this->checkBeforeRegister($data['student_id'], $data['thesis_phases_id'])) {
            return false; // No se cumplen las fases anteriores en cadena
        }

            // Crear la fase del proceso de tesis para el estudiante
        $thesisProcessPhaseStudent = $this->thesisProcessPhases->create($data);

        // Verificar si la fase requiere requisitos
        $this->asyncRequirements($data['thesis_phases_id'], $thesisProcessPhaseStudent);

        return $thesisProcessPhaseStudent;
    }


    protected function asyncRequirements(string $idPhase, ThesisProcessPhaseStudent $phaseProcess): void
    {
        // Obtener los requisitos de la fase
        $phase = $this->thesisPhasesService->getThesisPhase($idPhase);
        $requirements = $phase->requirements;

        // Verificar que existan requisitos para esta fase
        if ($requirements->isEmpty()) {
            return;
        }

        foreach ($requirements as $requirement) {
            $data = [
                'student_id' => $phaseProcess->student_id,
                'period_academic_id' => $phaseProcess->period_academic_id,
                'thesis_process_phases_id' => $phaseProcess->thesis_process_phases_id,
                'requirements_id' => $requirement->requirements_id,
                'requirements_data' => $requirement->description,
                'approved' => false,
                'state_now' => State::PENDING,
                'approved_by_user' => null,
                'url_file' => null,
                'send_date' => null,
                'approved_date' => null,
                'approved_role' => $requirement->approval_role,
            ];
            $this->requirementsStudentService->asyncRequirementsStudent($data);
        }
    }


    /**
     * Aprueba una fase del proceso de tesis si cumple con los requisitos.
     *
     * @param array $data Datos para actualizar la fase del proceso de tesis.
     * @param string $thesisProcessPhaseId ID de la fase del proceso de tesis a aprobar.
     * @return ThesisProcessPhaseStudent|bool La instancia actualizada de la fase del proceso de tesis si se aprueba, false en caso contrario.
     */
    public function aprovedThesisProcessPhase(string $thesisProcessPhaseId, string $userId): ThesisProcessPhaseStudent|bool
    {
        $thesisProcessPhase = $this->thesisProcessPhases->find($thesisProcessPhaseId);

        if ($this->checkRequerimentsThesisPhases($thesisProcessPhase)) {
            $thesisProcessPhase->update([
                'approval' => true,
                'date_approved' => now(),
                'state_now' => State::APPROVED,
                'updated_by_user' => $userId,
                'date_end' => now()
            ]);
            return $thesisProcessPhase;
        }

        return false;
    }
    /**
     * Verifica si la fase del proceso de tesis cumple con los requisitos.
     *
     * @param ThesisProcessPhaseStudent $model La instancia del modelo de la fase del proceso de tesis a verificar.
     * @return bool True si cumple con los requisitos, false en caso contrario.
     */
    protected function checkRequerimentsThesisPhases(ThesisProcessPhaseStudent $model): bool
    {
        $requeriments = $model->checkRequerimentsPhase();
        if ($requeriments) {
            return true;
        }
        return false;
    }


    protected function checkThesisPhaseAproval(string $studentId, string $moduleId, string $phaseId):bool
    {
        return true;
    }

    public function checkBeforeRegister(string $studentId, string $nextPhaseId): bool
    {
        // Obtener la fase siguiente a la que el estudiante desea registrarse, con el orden de módulo y fase
        $nextPhase = ThesisPhase::with(['module', 'order'])
            ->where('thesis_phases_id', $nextPhaseId)
            ->first();

        // Si la fase no existe, devolvemos falso
        if (!$nextPhase) {
            return false;
        }

        // Obtener todas las fases del proceso de tesis del estudiante en orden hasta la fase deseada
        $completedPhases = $this->thesisProcessPhases->where('student_id', $studentId)
            ->where('approval', true) // Solo fases aprobadas
            ->with([
                'phase' => function ($query) {
                    $query->with(['module:thesis_module_id:,order', 'order:order_phases_thesis_id,order']); // Cargar orden del módulo y fase
                }
            ])
            ->get();

        // Ordenar las fases aprobadas por el orden del módulo y luego por el orden de la fase
        $completedPhases = $completedPhases->sortBy([
            fn($phase) => $phase->phase->module->order,
            fn($phase) => $phase->phase->order->order,
        ]);

        // Verificar la cadena de aprobaciones
        $isInSequence = true;
        foreach ($completedPhases as $completedPhase) {
            if (
                $completedPhase->phase->module->order > $nextPhase->module->order ||
                ($completedPhase->phase->module->order == $nextPhase->module->order &&
                $completedPhase->phase->order->order >= $nextPhase->order->order)
            ) {
                // Si encontramos una fase fuera de orden, rompemos la secuencia
                $isInSequence = false;
                break;
            }
        }

        return $isInSequence;
    }

    public function phasesGroupByModule(string $studentId) : Collection
    {
        $thesisProcess = $this->findThesisProcessById($studentId);
        $phasesGroupedByModule = $thesisProcess->getStudentPhasesGroupedByModule();
        return $phasesGroupedByModule;
    }

    public function dataDashboard(string $studentId): array
    {
        // Obtener el proceso del estudiante, agrupado por módulo y fase
        $data_student = $this->phasesGroupByModule($studentId);

        // Obtener todos los módulos y sus fases
        $phase_module = app(ThesisPhasesServiceInterface::class)->getPhasesModule();

        // Formato de salida
        $result = [];

        foreach ($phase_module as $moduleName => $phases) {
            $moduleData = [
                'module_name' => $moduleName,
                'module_order' => $phases->first()->module_order,
                'phases' => []
            ];

            foreach ($phases as $phase) {
                $phaseData = [
                    'thesis_process_phases_id' => null,
                    'phase_name' => $phase->phase_name,
                    'phase_order' => $phase->phase_order,
                    'approval' => false, // Valor predeterminado para fases no completadas por el estudiante
                    'state_now' => State::NOT_ENABLED,
                ];

                // Verificar si el estudiante ha completado esta fase
                if (isset($data_student[$moduleName])) {
                    $studentPhase = $data_student[$moduleName]->firstWhere('phase_name', $phase->phase_name);
                    if ($studentPhase) {
                        $phaseData['thesis_process_phases_id'] = $studentPhase->thesis_process_phases_id;
                        $phaseData['approval'] = $studentPhase->approval; // Usar el estado real del estudiante
                        $phaseData['progress'] = $studentPhase->state_now == State::APPROVED ? 100 : $studentPhase->progress;
                        $phaseData['date_start'] = $studentPhase->date_start;
                        $phaseData['date_end'] = $studentPhase->date_end;
                        $phaseData['state_now'] = $studentPhase->state_now;
                    }
                }

                $moduleData['phases'][] = $phaseData;
            }

            $result[] = $moduleData;
        }

        return $result;
    }


    public function nextPhaseStudent(string $idStudent)
    {

        $thesisProcess = $this->thesisProcess->where('student_id', $idStudent)->first();

        $nextPhase = $thesisProcess->nextPhaseStudent();

        if (!$nextPhase) {
            throw new \Exception('No se encontró la siguiente fase del estudiante', 404);
        }

        return $nextPhase;
    }

    public function getTutorThesisProcessPhase(string $idProcess) : Teacher
    {
        $thesisProcess = $this->thesisProcess->where('thesis_process_id','=',$idProcess)->first();
        if (!$thesisProcess) {
            Log::info('No se encontró el proceso de tesis del estudiante');
            throw new \Exception('No se encontró el proceso de tesis del estudiante', 404);
        }
        return $thesisProcess->tutor;
    }

    public function asyncPreRequirements(string $idPhase, string $idStudent): void
    {
           // Obtener los requisitos de la fase
           $phase = $this->thesisPhasesService->getThesisPhase($idPhase);
           $preRequirements = $phase->preRequirements;

              // Verificar que existan requisitos para esta fase
              if ($preRequirements->isEmpty()) {
                  return;
              }

                foreach ($preRequirements as $preRequirement) {
                    $data = [
                        'student_id' => $idStudent,
                        'thesis_process_phases_id' => $idStudent,
                        'pre_requirements_id' => $preRequirement->pre_requirements_id,
                        'approved' => false,
                        'state_now' => State::PENDING,
                        'approved_by_user' => null,
                        'url_file' => null,
                        'send_date' => null,
                        'approved_date' => null,
                        'approved_role' => $preRequirement->approval_role,
                    ];
                    app(PreRequirementsStudentServiceInterface::class)->asyncPreRequirementsStudent($data);
                }

    }

}

