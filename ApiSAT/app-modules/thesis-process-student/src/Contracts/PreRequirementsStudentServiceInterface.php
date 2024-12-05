<?php
namespace Modules\ThesisProcessStudent\Contracts;
use App\Enums\StateEnum;

interface PreRequirementsStudentServiceInterface
{

    public function updateDocumentPreRequirementStudent(string $id, string $filePath): void;

    public function asyncPreRequirementsStudent($data);

    public function updateStatus(string $studentPreRequirementsId, StateEnum $status, string $updatedBy): void;
}
