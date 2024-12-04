<?php

namespace Modules\AnalystDegree\Services;

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
        return $this->studentForAnalyst->with(['thesisProcess.phasesStudent.preRequirements', 'thesisProcess.phasesStudent.phase','thesisProcess.phasesStudent.pre','user','thesisProcess.thesis','thesisProcess.tutor.user'])->whereHas('preRequirements', function ($query) {
            $query->where('status', '!=', State::APPROVED);
        })->paginate(10);
    }

}
