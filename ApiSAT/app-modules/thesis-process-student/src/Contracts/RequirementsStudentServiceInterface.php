<?php
namespace Modules\ThesisProcessStudent\Contracts;

use Modules\ThesisProcessStudent\Models\Requirements;

interface RequirementsStudentServiceInterface
{
    public function asyncRequirementsStudent(array $data): Requirements;
}
