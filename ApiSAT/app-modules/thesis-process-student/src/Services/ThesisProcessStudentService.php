<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Models\Academic\Thesis\ThesisProcessPhases;
use Illuminate\Support\Facades\Log;
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
        protected ThesisProcessPhaseStudent $thesisProcessPhases
    )
    {}

    /**
     * Registra una nueva fase del proceso de tesis.
     *
     * @param array $data Datos para crear la nueva fase del proceso de tesis.
     * @return ThesisProcessPhaseStudent La instancia creada de la fase del proceso de tesis.
     */
    public function registerThesisProcessPhase(array $data): ThesisProcessPhaseStudent
    {
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
}
