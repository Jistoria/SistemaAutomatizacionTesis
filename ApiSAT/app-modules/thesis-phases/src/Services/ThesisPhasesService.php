<?php

namespace Modules\ThesisPhases\Services;

use App\Models\Academic\Thesis\ThesisPhase;

class ThesisPhasesService
{
    public function __construct(
        protected ThesisPhase $thesisPhase
    )
    {}

    public function pluck() : array
    {
        return $this->thesisPhase->pluck('name', 'thesis_phases_id');
    }
}
