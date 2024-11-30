<?php

namespace App\Jobs;

use App\Utils\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ChangeApprovedPhaseStudent implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $approvedPhases = \App\Models\Academic\Thesis\ThesisProcessPhases::getPhasesAprovedRequirements();

        foreach ($approvedPhases as $phase) {
            Log::info('Aprobando fase de estudiante', ['phase' => $phase, 'date' => now()]);
            \App\Models\Academic\Thesis\ThesisProcessPhases::find($phase)->update([
                'state_now' => State::APPROVED,
                'approval' => true,
                'date_end' => now(),
            ]);
        }
    }
}
