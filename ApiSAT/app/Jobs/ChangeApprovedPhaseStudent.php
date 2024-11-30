<?php

namespace App\Jobs;

use App\Utils\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface;

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
            \App\Models\Academic\Thesis\ThesisProcessPhases::find($phase)->update([
                'state_now' => State::APPROVED,
                'approval' => true,
                'date_end' => now(),
            ]);

            app(ThesisProcessStudentServiceInterface::class)->asyncPreRequirements($phase->thesis_phases_id, $phase->student_id);
        }
    }
}
