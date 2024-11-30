<?php
namespace Modules\ThesisProcessStudent\Contracts;

interface PreRequirementsStudentServiceInterface
{

    public function updateDocumentPreRequirementStudent(string $id, string $filePath): void;

    public function asyncPreRequirementsStudent($data);
}
