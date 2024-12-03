<?php

namespace Modules\AnalystDegree\Models;

use App\Models\Academic\Student\Student;
use App\Models\Academic\Thesis\Requirement\PreRequirementsStudent;


class StudentForAnalyst extends Student
{


    public function preRequirements()
    {
        return $this->hasMany(PreRequirementsStudent::class, 'student_id', 'student_id');
    }
}
