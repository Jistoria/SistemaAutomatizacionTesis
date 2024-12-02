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
    public function getStudents(int $paginate = null, string $search = null, string $order = null)
    {
        $data = $this->students_process()
    ->join('thesis_titles', 'thesis_process.thesis_id', '=', 'thesis_titles.thesis_id')
    ->join('period_academic', 'thesis_process.period_academic_id', '=', 'period_academic.period_academic_id')
    ->join('students', 'thesis_process.student_id', '=', 'students.student_id')
    ->join('users', 'students.student_id', '=', 'users.id')
    ->join('thesis_process_phases', 'thesis_process.thesis_process_id', '=', 'thesis_process_phases.thesis_process_id')
    ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
    ->leftJoin('requirements', 'thesis_phases.thesis_phases_id', '=', 'requirements.thesis_phases_id')
    ->leftJoin('student_requirements', function ($join) {
        $join->on('requirements.requirements_id', '=', 'student_requirements.requirements_id')
             ->on('students.student_id', '=', 'student_requirements.student_id');
    })
    ->select([
        // Información básica del usuario
        'students.dni',
        'users.id',
        'users.name',
        'users.email',
        'users.created_at',
        'users.updated_at',
        'users.deleted_at',

        // Información de la tesis
        'thesis_titles.thesis_id',
        'thesis_titles.title',

        // Información de la fase actual
        'thesis_phases.thesis_phases_id',
        'thesis_phases.name as phase_name',
        'thesis_process_phases.thesis_process_phases_id',
        'thesis_process_phases.state_now as phase_state_now',

        // Información del periodo académico
        'period_academic.period_academic_id',
        'period_academic.name as period_academic_name',

        // Información del proceso de tesis
        'thesis_process.thesis_process_id',
        'thesis_process.student_id',
        'thesis_process.teacher_id',
        'thesis_process.state_now',
        'thesis_process.date_start',
        'thesis_process.date_end',
        'thesis_process.created_at',
        'thesis_process.updated_at',

        // Requerimientos del estudiante
        DB::raw('COUNT(requirements.requirements_id) as total_requirements'),
        DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::APPROVED . '\' THEN 1 ELSE 0 END) as total_requirements_approved'),
        DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::SENT . '\' THEN 1 ELSE 0 END) as total_requirements_sent'),
        DB::raw('SUM(CASE WHEN student_requirements.status = \'' . State::PENDING . '\' THEN 1 ELSE 0 END) as total_requirements_pending'),
    ])
    ->where('thesis_process_phases.state_now', '=', State::IN_PROCESS);

            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('users.name', 'ilike', '%' . $search . '%')
                          ->orWhere('thesis_titles.title', 'ilike', '%' . $search . '%');
                });
            }
            $data
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

                'thesis_phases.thesis_phases_id',
                'thesis_phases.name',
                'thesis_process_phases.thesis_process_phases_id',
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
            ->orderBy('users.name', $order ?? 'asc');

            return $paginate ? $data->paginate($paginate) : $data->get();
    }

    public function detailsStudent(string $student_id, string $thesis_phase_id)
    {

        $student = $this->students_process()
            ->join('thesis_process_phases', 'thesis_process.thesis_process_id', '=', 'thesis_process_phases.thesis_process_id')
            ->join('students', 'thesis_process.student_id', '=', 'students.student_id')
            ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->join('requirements', 'thesis_phases.thesis_phases_id', '=', 'requirements.thesis_phases_id')
            ->leftJoin('student_requirements', 'requirements.requirements_id', '=', 'student_requirements.requirements_id')
            ->join('users', 'students.student_id', '=', 'users.id')
            ->select(
                    'users.id as id',
                    'users.name',
                    'users.email',
                    'thesis_process_phases.thesis_process_phases_id',
                    'thesis_phases.name as phase_name',
                    'thesis_phases.thesis_phases_id',
                    'thesis_process_phases.state_now as phase_state_now',
                DB::raw('JSON_AGG(
                    JSON_BUILD_OBJECT(
                        \'id\', requirements.requirements_id,
                        \'name\', requirements.name,
                        \'status\', student_requirements.status,
                        \'approved\', student_requirements.approved,
                        \'url_file\', student_requirements.url_file,
                        \'send_date\', student_requirements.send_date
                    )) as requirements')
            )
            ->where('students.student_id', $student_id)
            ->where('thesis_process_phases.thesis_phases_id', $thesis_phase_id)
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'thesis_process_phases.thesis_process_phases_id',
                'thesis_phases.name',
                'thesis_phases.thesis_phases_id',
                'thesis_process_phases.state_now'
            )
            ->get();

        $student->transform(function ($item) {
            $item->requirements = json_decode($item->requirements, true);
            return $item;
        });

        return $student->first();
    }

}
