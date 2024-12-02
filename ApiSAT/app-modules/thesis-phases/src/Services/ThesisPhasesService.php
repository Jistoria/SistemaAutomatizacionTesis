<?php

namespace Modules\ThesisPhases\Services;

use App\Models\Academic\Thesis\ThesisPhase;
use Illuminate\Support\Collection;

class ThesisPhasesService
{
    public function __construct(
        protected ThesisPhase $thesisPhase
    )
    {}

    public function pluck() : Collection
    {
        return $this->thesisPhase->pluck('name', 'thesis_phases_id');
    }
}
