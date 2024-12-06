<?php
namespace Modules\ThesisProcessStudent\Contracts;

use App\Enums\StateEnum;
use App\Utils\State;
use Illuminate\Database\Eloquent\Collection;
use Modules\ThesisProcessStudent\Models\Requirements;

/**
 * Interface RequirementsStudentServiceInterface
 *
 * Esta interfaz define los métodos para gestionar los requisitos de los estudiantes en el proceso de tesis.
 */
interface RequirementsStudentServiceInterface
{
    /**
     * Procesa asincrónicamente los requisitos del estudiante.
     *
     * @param array $data Datos necesarios para procesar los requisitos.
     * @return Requirements Objeto que representa los requisitos procesados.
     */
    public function asyncRequirementsStudent(array $data): Requirements;

    /**
     * Obtiene los requisitos de la fase del estudiante.
     *
     * @param string $id Identificador del estudiante.
     * @return Collection Colección de requisitos de la fase del estudiante.
     */
    public function requirementsPhaseStudent(string $id): Collection;

    /**
     * Actualiza el documento de un requisito del estudiante.
     *
     * @param string $id Identificador del requisito del estudiante.
     * @param string $document Documento actualizado.
     * @return void
     */
    public function updateDocumentRequirementStudent(string $id, string $document): void;

    /**
     * Actualiza el estado de un requisito del estudiante.
     *
     * @param string $studentRequirementsId Identificador del requisito del estudiante.
     * @param StateEnum $status Nuevo estado del requisito.
     * @param string $updatedBy Usuario que realiza la actualización.
     * @return void
     */
    public function updateStatus(string $studentRequirementsId, StateEnum $status, string $updatedBy): void;
}
