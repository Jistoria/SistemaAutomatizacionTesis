<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Enums\StateEnum;
use App\Utils\State;
use Illuminate\Database\Eloquent\Collection;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;
use Modules\ThesisProcessStudent\Models\Requirements;

class RequirementsStudentService implements RequirementsStudentServiceInterface
{
    public function __construct(
        protected Requirements $requirements
    )
    {}

    public function asyncRequirementsStudent(array $data): Requirements
    {
        return $this->requirements->create($data);
    }

    public function requirementsPhaseStudent(string $id): Collection
    {
        return $this->requirements->dataRequirementsStudent($id);
    }

    public function updateDocumentRequirementStudent(string $id, string $document): void
    {
        $this->requirements->find($id)->update(['url_file' => $document, 'send_date' => now(), 'status' => State::SENT]);
    }

    public function updateStatus(string $studentRequirementsId, StateEnum $status, string $updatedBy): void
    {

        $requirement = $this->requirements->find($studentRequirementsId);

        if ($requirement === null) {
            throw new \Exception('Requisito no encontrado');
        }

        if ($this->checkPhaseApproved($requirement)) {
            throw new \Exception('No se puede modificar el estado de un requisito de una fase aprobada');
        }

        $requirement->status = $status;

        if ($status->value === State::APPROVED) {
            $requirement->approved = true;
            $requirement->approved_date = now();
            $requirement->approved_by_user = $updatedBy;
        }

        if ($status->value === State::REJECTED) {
            app(ImportDataFileServiceInterface::class)->deleteFile($requirement->url_file);
            $requirement->url_file = null;
            $requirement->approved = false;
            $requirement->approved_date = null;
            $requirement->approved_by_user = null;
        }

        $requirement->save();
    }


    public function checkPhaseApproved(Requirements $requirement): bool
    {
        return $requirement->checkPhaseApproved($requirement->thesis_process_phases_id);
    }


}
