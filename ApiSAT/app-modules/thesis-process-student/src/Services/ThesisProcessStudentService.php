<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Models\Academic\Thesis\ThesisPhase;
use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Academic\Thesis\ThesisProcessPhases;
use Illuminate\Support\Facades\Log;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;
use Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface;
use Modules\ThesisProcessStudent\Models\ThesisProcessPhaseStudent;

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
        protected ThesisProcess $thesisProcess,
        protected ThesisPhasesServiceInterface $thesisPhasesService
    )
    {}

    public function findThesisProcessById(string $id)
    {
        return $this->thesisProcess->where('student_id', $id)->with('phasesStudent')->first();
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

        return $this->thesisProcessPhases->create($data);
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
                'state_now' => 'Aprobado',
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



}

