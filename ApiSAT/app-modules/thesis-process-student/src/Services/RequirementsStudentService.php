<?php

namespace Modules\ThesisProcessStudent\Services;

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

    public function updateDocumentRequirementStudent(int $id, string $document): void
    {
        $this->requirements->find($id)->update(['url_file' => $document, 'send_date' => now()]);
    }


}
