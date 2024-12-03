<?php

namespace Modules\User\Services;

use App\Models\Academic\Student\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function getStudentWithRelations(array|string $relations, string $studentId): Student
    {
        return $this->student->with((array) $relations)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getPaginatedStudentsWithRelations(array|string $relations, int $pagination): LengthAwarePaginator
    {
        return $this->student->with((array) $relations)
            ->paginate($pagination);
    }

    public function eloquentStudentsWithRelations(array|string $relations, int $pagination): \Illuminate\Database\Eloquent\Builder
    {
        return $this->student->with((array) $relations);
    }



    public function getStudentByDni(string $dni): Student
    {
        return $this->student->where('dni', $dni)->first();
    }

    public function getStudentById(string $studentId): Student
    {
        return $this->student->where('student_id', $studentId)->first();
    }

    public function getStudentByDegreeId(string $degreeId): Student
    {
        return $this->student->where('degree_id', $degreeId)->first();
    }

    public function getStudentByThesisId(string $thesisId): Student
    {
        return $this->student->where('thesis_id', $thesisId)->first();
    }

    public function getStudentByEnrollmentDate(string $enrollmentDate): Student
    {
        return $this->student->where('enrollment_date', $enrollmentDate)->first();
    }
}
