<?php

namespace Modules\ThesisProcessStudent\Models;

use App\Models\Academic\Thesis\Requirement\RequirementsStudent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Requirements extends RequirementsStudent
{
    public static function dataRequirementsStudent($processPhaseId): Collection
    {
        return self::where('thesis_process_phases_id', $processPhaseId)
            ->join('requirements', 'student_requirements.requirements_id', '=', 'requirements.requirements_id')
            ->select([
                'student_requirements.student_requirements_id',
                'student_requirements.status',
                'student_requirements.student_id',
                'student_requirements.period_academic_id',
                'student_requirements.thesis_process_phases_id',
                'student_requirements.requirements_data',
                'student_requirements.approved',
                'student_requirements.approved_by_user',
                'student_requirements.url_file',
                'student_requirements.send_date',
                'student_requirements.approved_date',
                'student_requirements.approved_role',
                'requirements.requirements_id as requirements_id',
                'requirements.name as name',
                'requirements.description as description'
            ])
            ->orderBy('student_requirements.student_requirements_id')
            ->get();
    }
}
