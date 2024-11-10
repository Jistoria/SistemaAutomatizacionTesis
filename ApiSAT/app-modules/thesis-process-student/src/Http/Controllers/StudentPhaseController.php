<?php

namespace Modules\ThesisProcessStudent\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ThesisProcessStudent\Services\ThesisProcessStudentService;

class StudentPhaseController
{
    public function __construct(
        protected ThesisProcessStudentService $studentPhaseService
    )
    {}



}
