<?php
namespace Modules\ThesisProcessStudent\Contracts;

use Modules\ThesisProcessStudent\Models\ThesisProcessPhaseStudent;

interface ThesisProcessStudentServiceInterface
{
    public function registerThesisProcessPhase(array $data): ThesisProcessPhaseStudent;
    public function aprovedThesisProcessPhase(string $thesisProcessPhaseId, string $userId): ThesisProcessPhaseStudent|bool;
}
