<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisProcess;
use App\Utils\State;
use Modules\Thesis\Contracts\ThesisProcessServiceInterface;

class ThesisProcessService implements ThesisProcessServiceInterface
{
    public function __construct(
        protected ThesisProcess $thesisProcess
    )
    {}

    public function firstOrCreateThesisProcess(array $data, string $userId): ThesisProcess
    {
        $thesisProcess = $this->thesisProcess->firstOrCreate(
            [
                'teacher_id' => $data['teacher_id'],
                'student_id' => $data['student_id'],
                'thesis_id' => $data['thesis_id']
            ],
            [
                'teacher_id' => $data['teacher_id'],
                'student_id' => $data['student_id'],
                'thesis_id' => $data['thesis_id'],
                'period_academic_id' => $data['period_academic_id'],
                'state_now' => State::IN_PROCESS,
                'date_start' => $data['date_start'],
                'date_end' => $data['date_end'],
                'created_by_user' => $userId,
                'updated_by_user' => $userId
            ]
        );

        return $thesisProcess;
    }
}
