<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisPhase;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;

class ThesisPhasesService implements ThesisPhasesServiceInterface
{
    public function __construct(
        protected ThesisPhase $thesisPhase
    )
    {}

    public function getThesisPhase(string $thesisPhaseId): ThesisPhase
    {
        return $this->thesisPhase->find($thesisPhaseId);
    }

    public function getThesisPhaseByName(string $name): ThesisPhase
    {
        return $this->thesisPhase->where('name', $name)->first();
    }

    public function getThesisPhaseByOrder(int $orderPhase, int $orderModule): ThesisPhase
    {
        $thesisPhase = $this->thesisPhase->whereHas('module', function ($query) use ($orderModule) {
            $query->where('order', $orderModule);
        })->whereHas('order', function ($query) use ($orderPhase) {
            $query->where('order', $orderPhase);
        })->first();

        return $thesisPhase;
    }
}
