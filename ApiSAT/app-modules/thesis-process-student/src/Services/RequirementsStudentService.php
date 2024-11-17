<?php

namespace Modules\ThesisProcessStudent\Services;

use App\Enums\StateEnum;
use App\Utils\State;
use Illuminate\Database\Eloquent\Collection;
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
        $requirement->status = $status;
        $requirement->updated_by = $updatedBy;

        if ($status === State::APPROVED) {
            $requirement->approved = true;
            $requirement->approved_date = now();
            $requirement->approved_by = $updatedBy;
        }

        $requirement->save();
    }



}
