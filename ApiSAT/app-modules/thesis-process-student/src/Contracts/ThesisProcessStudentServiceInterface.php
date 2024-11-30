<?php
namespace Modules\ThesisProcessStudent\Contracts;

use App\Models\Academic\Teacher\Teacher;
use Modules\ThesisProcessStudent\Models\ThesisProcessPhaseStudent;

/**
 * Interfaz ThesisProcessStudentServiceInterface
 *
 * Esta interfaz define los métodos necesarios para gestionar el proceso de tesis de un estudiante.
 */
interface ThesisProcessStudentServiceInterface
{
    /**
     * Registra una fase del proceso de tesis.
     *
     * @param array $data Datos necesarios para registrar la fase del proceso de tesis.
     * @return ThesisProcessPhaseStudent|bool Retorna una instancia de ThesisProcessPhaseStudent si se registra correctamente, o false en caso contrario.
     */
    public function registerThesisProcessPhase(array $data): ThesisProcessPhaseStudent|bool;

    /**
     * Aprueba una fase del proceso de tesis.
     *
     * @param string $thesisProcessPhaseId ID de la fase del proceso de tesis.
     * @param string $userId ID del usuario que aprueba la fase.
     * @return ThesisProcessPhaseStudent|bool Retorna una instancia de ThesisProcessPhaseStudent si se aprueba correctamente, o false en caso contrario.
     */
    public function aprovedThesisProcessPhase(string $thesisProcessPhaseId, string $userId): ThesisProcessPhaseStudent|bool;

    /**
     * Obtiene el tutor de una fase del proceso de tesis.
     *
     * @param string $thesisProcessPhaseId ID de la fase del proceso de tesis.
     * @return Teacher Retorna una instancia de Teacher que es el tutor de la fase del proceso de tesis.
     */
    public function getTutorThesisProcessPhase(string $thesisProcessPhaseId): Teacher;

    /**
     * Sincroniza los prerrequisitos de una fase del proceso de tesis de manera asíncrona.
     *
     * @param string $idPhase ID de la fase del proceso de tesis.
     * @param string $idStudent ID del estudiante.
     * @return void
     */
    public function asyncPreRequirements(string $idPhase, string $idStudent): void;
}
