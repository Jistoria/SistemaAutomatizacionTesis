<?php

namespace Modules\ThesisTutor\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\ThesisTutor\Models\Tutor;

class ThesisTutorService
{
    public function __construct(
        protected Tutor $teacher
    )
    {}

    public function getMyStudents(string $user) : ?Collection
    {
        $studendts = $this->teacher->where('teacher_id', $user)->first()->getStudents();

        return $studendts;
    }

    public function changeStatusRequirementStudent(string $user, string $student_requirements_id) : void
    {
        $this->teacher->where('teacher_id', $user)->first()->changeStatusRequirement($student_requirements_id);
    }

}
