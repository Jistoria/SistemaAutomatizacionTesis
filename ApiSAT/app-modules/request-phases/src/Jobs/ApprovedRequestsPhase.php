<?php

namespace Modules\RequestPhases\Jobs;

use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Academic\Thesis\ThesisProcessPhases;
use App\Utils\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface;

class ApprovedRequestsPhase implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Collection $allRequests,
        protected string $id
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $now_period = app(PeriodAcademicServiceInterface::class)->getNowPeriodAcademic();

        foreach ($this->allRequests as $request) {
            $thesis_process_phase_student = app(ThesisProcessStudentServiceInterface::class);
            $tutor = $thesis_process_phase_student->getTutorThesisProcessPhase($request->thesis_process_id);

            $thesis_process_phase_student->registerThesisProcessPhase([
                'thesis_process_id' => $request->thesis_process_id,
                'student_id' => $request->student_id,
                'thesis_phases_id' => $request->requested_phase_id,
                'date_start' => now(),
                'state_now' => State::IN_PROCESS,
                'period_academic_id' => $now_period->period_academic_id,
                'teacher_id' => $tutor->teacher_id,
                'created_by_user' => $this->id,
            ]);
        }
    }
}
