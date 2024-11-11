<?php

namespace Modules\ThesisProcessStudent\Services;

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

    public function requirementsPhaseStudent(string $id): Requirements
    {
        return $this->requirements->where('thesis_process_phases_id', $id)->first();
    }


}
