<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Enums\StateEnum;
use App\Utils\State;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ThesisProcessStudent\Contracts\PreRequirementsStudentServiceInterface;
use Modules\ThesisProcessStudent\Models\PreRequirements;
/**
 * Clase PreRequirementsStudentService que implementa la interfaz PreRequirementsStudentServiceInterface.
 *
 * Esta clase proporciona servicios relacionados con los requisitos previos de los estudiantes.
 */
class PreRequirementsStudentService implements PreRequirementsStudentServiceInterface
{
    /**
     * Constructor de la clase.
     *
     * @param PreRequirements $preRequirements Instancia de la clase PreRequirements.
     */
    public function __construct(
        protected PreRequirements $preRequirements
    )
    {}

    /**
     * Actualiza el documento de un requisito previo del estudiante.
     *
     * @param string $id Identificador del requisito previo.
     * @param string $document URL del documento.
     * @return void
     */
    public function updateDocumentPreRequirementStudent(string $id, string $document): void
    {
        $this->preRequirements->find($id)->update(['url_file' => $document, 'send_date' => now(), 'status' => State::SENT]);
    }

    /**
     * Sincroniza los requisitos previos del estudiante de manera asíncrona.
     *
     * @param array $data Datos de los requisitos previos del estudiante.
     * @return void
     */
    public function asyncPreRequirementsStudent($data)
    {
        $this->preRequirements->create($data);
    }

    public function updateStatus(string $studentPreRequirementsId, StateEnum $status, string $updatedBy): void
    {
        $pre_requirement = $this->preRequirements->find($studentPreRequirementsId);

        if ($pre_requirement === null) {
            throw new \Exception('Requisito no encontrado', 404);
        }

        if ($pre_requirement->status === State::REJECTED && $status->value === State::APPROVED) {
            throw new \Exception('No se puede aprobar un requisito que ha sido rechazado', 400);
        }

        if ($pre_requirement->url_file === null) {
            throw new \Exception('No se cambiar de estado a un requisito que no ha proporcionado documento', 400);
        }


        $pre_requirement->status = $status;

        if ($status->value === State::APPROVED) {
            $pre_requirement->approved = true;
            $pre_requirement->approved_date = now();
            $pre_requirement->approved_by_user = $updatedBy;
        }

        if ($status->value === State::REJECTED) {
            app(ImportDataFileServiceInterface::class)->deleteFile($pre_requirement->url_file);
            $pre_requirement->url_file = null;
            $pre_requirement->approved = false;
            $pre_requirement->approved_date = null;
            $pre_requirement->approved_by_user = null;
        }


        $pre_requirement->save();
        //event(new RequirementStatusChanged($pre_requirement->thesisProcessPhase->teacher->teacher_id, $pre_requirement->thesisProcessPhase->student->user->name, $status, $pre_requirement->requirement->name));
    }
}
