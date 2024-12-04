<?php

namespace Modules\User\Services;

use App\Models\Academic\Teacher\Teacher;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherService
{
    public function __construct(
        protected Teacher $teacher
    )
    {
        //
    }

    public function getPaginatedTeachersWithRelations(string|array $relations, int $perPage): LengthAwarePaginator
    {
        return $this->teacher->with((array) $relations)
            ->withCount('students_process')
            ->paginate($perPage);
    }
   
}
