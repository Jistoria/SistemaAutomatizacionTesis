<?php

namespace Modules\ThesisPhases\Services;

use App\Models\Academic\Thesis\ThesisPhase;
use Illuminate\Support\Collection;
use App\Utils\State;

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

    public function dataDashboard()
    {
        $phasesInProcessData = $this->thesisPhase->withCount(['thesisProcessPhase'=> function($query){
            $query->where('state_now', State::IN_PROCESS);
        }])->get();

        $phasesApprovedData = $this->thesisPhase->withCount(['thesisProcessPhase'=>function($query){
            $query->where('state_now', State::APPROVED);
        }])->get();


        return [
            'phasesInProcessData' => $phasesInProcessData,
            'phasesApprovedData' => $phasesApprovedData,
        ];
    }
}
