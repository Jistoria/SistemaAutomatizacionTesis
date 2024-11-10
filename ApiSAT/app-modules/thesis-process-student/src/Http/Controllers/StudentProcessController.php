<?php

namespace Modules\ThesisProcessStudent\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ThesisProcessStudent\Services\ThesisProcessStudentService;

class StudentProcessController
{

    public function __construct(
        protected ThesisProcessStudentService $studentPhaseService
    )
    {}

    public function getDataDashboard(Request $request)
    {
        try{
            $processPhase = $this->studentPhaseService->dataDashboard($request->user()->id);

            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
}
