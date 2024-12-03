<?php

namespace App\Jobs;

use App\Events\ApprovedPhaseEvent;
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
        try{
            $approvedPhases = \App\Models\Academic\Thesis\ThesisProcessPhases::getPhasesAprovedRequirements();

            foreach ($approvedPhases as $phase) {

                $model = \App\Models\Academic\Thesis\ThesisProcessPhases::find($phase);

                $nextPhase = $model->phase->order->next_phases_id;

                app(ThesisProcessStudentServiceInterface::class)->asyncPreRequirements($nextPhase, $model->student_id, $model->thesis_process_phases_id);

                $model->update([
                    'state_now' => State::APPROVED,
                    'approval' => true,
                    'date_end' => now(),
                ]);

                event(new ApprovedPhaseEvent($model->student_id, $model->phase->name));


            }
        } catch (\Exception $e) {
            Log::error('Error al cambiar de fase aprobada del estudiante: ' . $e->getMessage());
        }
    }
}
