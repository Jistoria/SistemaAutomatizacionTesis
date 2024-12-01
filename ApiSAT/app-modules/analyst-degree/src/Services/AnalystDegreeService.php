<?php

namespace Modules\AnalystDegree\Services;

use App\Models\Academic\Student\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AnalystDegreeService
{
    public function __construct()
    {}

    public function getMyStudents() : LengthAwarePaginator
    {
        return app(\Modules\User\Contracts\StudentServiceInterface::class)
            ->getPaginatedStudentsWithRelations([
                'degree',
                'thesis',
                'user',
                'thesisProcess.tutor.user'], 5);
    }

    public function getStudentDetails(string $student_id) : Student
    {

        return app(\Modules\User\Contracts\StudentServiceInterface::class)
            ->getStudentWithRelations([''], $student_id);
    }
}
