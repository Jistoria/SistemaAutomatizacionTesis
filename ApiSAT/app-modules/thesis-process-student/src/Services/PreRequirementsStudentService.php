<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Utils\State;
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
}
