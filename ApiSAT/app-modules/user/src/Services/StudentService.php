<?php

namespace Modules\User\Services;

use App\Models\Academic\Student\Student;
use Modules\User\Contracts\StudentServiceInterface;

class StudentService implements StudentServiceInterface
{
    public function __construct(
        protected Student $student
    )
    {
        //
    }

    public function firstOrCreateStudent(array $data, string $userId): Student
    {
        $student = $this->student->firstOrCreate([
            'dni' => $data['dni'],
        ],[
            'student_id' => $data['student_id'],
            'degree_id' => $data['degree_id'],
            'thesis_id' => $data['thesis_id'],
            'enrollment_date' => $data['enrollment_date'],
            'created_by_user' => $userId,
            'updated_by_user' => $userId,
        ]);

        return $student;
    }
}
