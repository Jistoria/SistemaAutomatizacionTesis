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

    public function getProcessAllData(Request $request)
    {
        $processPhase = $this->studentPhaseService->findThesisProcessById($request->user()->id);

        return ApiResponse::success($processPhase);
    }


}
