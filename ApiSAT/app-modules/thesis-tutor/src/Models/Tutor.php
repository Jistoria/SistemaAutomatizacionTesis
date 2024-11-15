<?php

namespace Modules\ThesisTutor\Models;

use App\Models\Academic\Teacher\Teacher;

class Tutor extends Teacher
{
     /**
     * Obtener los estudiantes asociados con el tutor.
     */
    public function getStudents()
    {
        return $this->students_process()
            ->join('thesis_titles', 'thesis_process.thesis_id', '=', 'thesis_titles.thesis_id')
            ->join('period_academic', 'thesis_process.period_academic_id', '=', 'period_academic.period_academic_id')
            ->join('students', 'thesis_process.student_id', '=', 'students.student_id')
            ->join('users', 'students.student_id', '=', 'users.id')
            ->select([
                'thesis_process.thesis_process_id',
                'thesis_process.student_id',
                'thesis_process.teacher_id',
                'thesis_process.state_now',
                'thesis_process.date_start',
                'thesis_process.date_end',
                'thesis_process.created_at',
                'thesis_process.updated_at',
                'thesis_titles.thesis_id',
                'thesis_titles.title',
                'period_academic.period_academic_id',
                'period_academic.name as period_academic_name',
                'students.student_id',
                'students.dni',
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'users.deleted_at',
            ])
            ->get();
    }
}
