<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisProcess;
use App\Utils\State;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
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
    

    public function getPaginatedThesisProcessesWithRelations(int $perPage)
{
    $data = $this->thesisProcess
        ->join('students', 'thesis_process.student_id', '=', 'students.student_id')
        ->join('teachers', 'thesis_process.teacher_id', '=', 'teachers.teacher_id')
        ->join('thesis_titles', 'thesis_process.thesis_id', '=', 'thesis_titles.thesis_id')
        ->join('users as student_user', 'students.student_id', '=', 'student_user.id')
        ->join('users as teacher_user', 'teachers.teacher_id', '=', 'teacher_user.id')
        ->join('category_thesis', 'thesis_titles.thesis_id', '=', 'category_thesis.thesis_id')
        ->join('category_area', 'category_thesis.category_area_id', '=', 'category_area.category_area_id')
        ->select(
            'thesis_titles.title as thesis_title',
            DB::raw('STRING_AGG(DISTINCT student_user.name, \', \') as student_names'),
            DB::raw('STRING_AGG(DISTINCT teacher_user.name, \', \') as teacher_names'),
            DB::raw('STRING_AGG(DISTINCT category_area.name, \', \') as category_areas'),
            DB::raw('MAX(thesis_process.date_start) as latest_start_date'),
            DB::raw('COUNT(thesis_process.thesis_process_id) as process_count'),
            DB::raw('COUNT(category_area.category_area_id) as category_count')
        )
        ->groupBy('thesis_titles.title')
        ->paginate($perPage);

    return $data;
}

    
}
