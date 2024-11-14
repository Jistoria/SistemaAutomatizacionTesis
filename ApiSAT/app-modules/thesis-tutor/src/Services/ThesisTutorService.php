<?php

namespace Modules\ThesisTutor\Services;

use App\Models\Academic\Teacher\Teacher;
use Illuminate\Database\Eloquent\Collection;

class ThesisTutorService
{
    public function __construct(
        protected Teacher $teacher
    )
    {}

    public function getMyStudents(string $user): Collection
    {
        $studendts = $this->teacher->where('teacher_id', $user)->first()->students->user;

        return $studendts;
    }

}
