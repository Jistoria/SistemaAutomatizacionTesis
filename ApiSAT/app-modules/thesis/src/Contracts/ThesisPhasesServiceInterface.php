<?php
namespace Modules\Thesis\Contracts;

use App\Models\Academic\Thesis\ThesisPhase as ThesisPhaseModel;
use Modules\Thesis\Models\ThesisPhase;

interface ThesisPhasesServiceInterface
{
    public function getThesisPhase(string $thesisPhaseId): ThesisPhaseModel;

    public function getThesisPhaseByName(string $name): ThesisPhaseModel;

    public function getThesisPhaseByOrder(int $orderPhase, int $orderModule): ThesisPhaseModel;
}
