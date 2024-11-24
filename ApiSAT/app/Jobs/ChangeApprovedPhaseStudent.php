<?php

namespace App\Jobs;

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
            $phase->update([
                'state_now' => \App\Utils\State::APPROVED,
                'approval' => true,
                'date_end' => now(),
            ]);
        }

        Log::info('Se han aprobado las fases de tesis que cumplieron con los requisitos.');
    }
}
