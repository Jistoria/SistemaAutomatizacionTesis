<?php
namespace Modules\ThesisProcessStudent\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\ThesisProcessStudent\Models\Requirements;

interface RequirementsStudentServiceInterface
{
    public function asyncRequirementsStudent(array $data): Requirements;

    public function requirementsPhaseStudent(string $id): Collection;

    public function updateDocumentRequirementStudent(string $id, string $document): void;
}
