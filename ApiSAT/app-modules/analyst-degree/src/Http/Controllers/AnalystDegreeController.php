<?php

namespace Modules\AnalystDegree\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\AnalystDegree\Services\AnalystDegreeService;

class AnalystDegreeController
{

    public function __construct(
        protected AnalystDegreeService $analystDegreeService
    )
    {}

    public function students()
    {
        try {
            $students = $this->analystDegreeService->getMyStudents();
            return ApiResponse::success($students);
        } catch (\Exception $e) {
            dd($e);
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }
    }

    // public function studentsDetails(Request $request, string $student_id)
    // {
    //     try {
    //         $student = $this->analystDegreeService->getStudentDetails($student_id);
    //         return ApiResponse::success($student);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), $e->getCode());
    //     }
    // }

    public function changeStatusPrerequeriments(Request $request, string $prerequeriments_id)
    {
        try {
            $prerequeriments = $this->analystDegreeService->changeStatusPrerequeriments($prerequeriments_id);
            return ApiResponse::success($prerequeriments);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }
    }
}
