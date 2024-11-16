<?php

namespace Modules\ThesisTutor\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ThesisTutor\Services\ThesisTutorService;

class ThesisTutorController
{
    public function __construct(
        private ThesisTutorService $thesisTutorService
    )
    {}

    public function myStudents(Request $request)
    {
        try{
            $students = $this->thesisTutorService->getMyStudents($request->user()->id, $request->input('pagination'));
            return ApiResponse::success($students);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function requirementsStudent(Request $request)
    {
        try{
            return ApiResponse::success('requirements_student');
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function changeStatusRequirementStudent(Request $request)
    {
        try{
            return ApiResponse::success('change_status_requirement_student');
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
}
