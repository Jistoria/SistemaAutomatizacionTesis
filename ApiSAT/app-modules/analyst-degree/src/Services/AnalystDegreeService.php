<?php

namespace Modules\AnalystDegree\Services;

use App\Enums\StateEnum;
use App\Models\Academic\Student\Student;
use App\Utils\State;
use Composer\XdebugHandler\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\AnalystDegree\Models\StudentForAnalyst;

class AnalystDegreeService
{
    public function __construct(
        protected StudentForAnalyst $studentForAnalyst
    )
    {}

    public function getMyStudents() : LengthAwarePaginator
    {
        return $this->studentForAnalyst->with(['preRequirements.preRequirement','user','thesisProcess.thesis','thesisProcess.tutor.user'])->whereHas('preRequirements', function ($query) {
            $query->where('status', '!=', State::APPROVED);
        })->paginate(10);
    }

    public function changeStatusPrerequeriments(string $user, string $student_requirements_id, StateEnum $status): void
    {
        $student = $this->studentForAnalyst->where('student_prerequirements_id', $student_requirements_id)->first();
        $student->preRequirements()->updateExistingPivot($student_requirements_id, ['status' => $status]);
    }


}
