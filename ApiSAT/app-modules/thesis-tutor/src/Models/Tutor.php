<?php

namespace Modules\ThesisTutor\Models;

use App\Models\Academic\Teacher\Teacher;
use App\Utils\State;
use Illuminate\Support\Facades\DB;

class Tutor extends Teacher
{
     /**
     * Obtener los estudiantes asociados con el tutor.
     */
    public function getStudents(int $paginate = null)
    {
        $data = $this->students_process()
            ->join('thesis_titles', 'thesis_process.thesis_id', '=', 'thesis_titles.thesis_id')
            ->join('period_academic', 'thesis_process.period_academic_id', '=', 'period_academic.period_academic_id')
            ->join('students', 'thesis_process.student_id', '=', 'students.student_id')
            ->join('users', 'students.student_id', '=', 'users.id')
            ->join('thesis_process_phases', 'thesis_process.thesis_process_id', '=', 'thesis_process_phases.thesis_process_id')
            ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->join('student_requirements', 'students.student_id', '=', 'student_requirements.student_id')
            ->join('requirements', 'student_requirements.requirements_id', '=', 'requirements.requirements_id')
            ->select([
                'students.dni',
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'users.deleted_at',

                'thesis_titles.thesis_id',
                'thesis_titles.title',

                'thesis_phases.name as phase_name',
                'thesis_process_phases.state_now as phase_state_now',

                'period_academic.period_academic_id',
                'period_academic.name as period_academic_name',

                'thesis_process.thesis_process_id',
                'thesis_process.student_id',
                'thesis_process.teacher_id',
                'thesis_process.state_now',
                'thesis_process.date_start',
                'thesis_process.date_end',
                'thesis_process.created_at',
                'thesis_process.updated_at',

                DB::raw('COUNT(student_requirements.student_requirements_id) as total_requirements'),
                DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::APPROVED . '\' THEN 1 ELSE 0 END) as total_requirements_approved'),
                DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::SENT . '\' THEN 1 ELSE 0 END) as total_requirements_sent'),
                DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::REJECTED . '\' THEN 1 ELSE 0 END) as total_requirements_rejected'),
                DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::IN_PROCESS . '\' THEN 1 ELSE 0 END) as total_requirements_in_process'),

                DB::raw('JSON_AGG(
                    JSON_BUILD_OBJECT(
                        \'student_requirements_id\', student_requirements.student_requirements_id,
                        \'requirements_id\', student_requirements.requirements_id,
                        \'requirements_data\', student_requirements.requirements_data,
                        \'approved\', student_requirements.approved,
                        \'approved_by_user\', student_requirements.approved_by_user,
                        \'url_file\', student_requirements.url_file,
                        \'send_date\', student_requirements.send_date,
                        \'approved_date\', student_requirements.approved_date,
                        \'approved_role\', student_requirements.approved_role,
                        \'status\', student_requirements.status,
                        \'requirement_name\', requirements.name
                    )
                ) as requirements')
            ])
            ->where('thesis_process_phases.state_now', '=', State::IN_PROCESS)
            ->groupBy([
                'students.dni',
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'users.deleted_at',

                'thesis_titles.thesis_id',
                'thesis_titles.title',

                'thesis_phases.name',
                'thesis_process_phases.state_now',

                'period_academic.period_academic_id',
                'period_academic.name',

                'thesis_process.thesis_process_id',
                'thesis_process.student_id',
                'thesis_process.teacher_id',
                'thesis_process.state_now',
                'thesis_process.date_start',
                'thesis_process.date_end',
                'thesis_process.created_at',
                'thesis_process.updated_at',
            ])
            ->orderBy('users.name');

            return $paginate ? $data->paginate($paginate) : $data->get();
    }


}
