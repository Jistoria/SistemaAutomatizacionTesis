<?php

namespace Modules\User\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\User\Services\StudentService;
use Modules\User\Services\TeacherService;

class UserController
{
    public function __construct(
        protected StudentService $studentService,
        protected TeacherService $teacherService    
    )
    {}

    public function students(Request $request)
    {
        try{
            $studentsPaginate = $this->studentService->getPaginatedStudentsWithRelations(['user','thesisProcess.thesis.categoryAreas','thesisProcess.tutor.user','thesisProcess.periodAcademic'], 20);
            return ApiResponse::success($studentsPaginate);
        }catch(\Exception $e){
            ApiResponse::error($e->getMessage(), 500 );
        }
    }

    public function teachers(Request $request)
    {
        try{

            $teachers = $this->teacherService->getPaginatedTeachersWithRelations(['user', 'categoryAreas', 'students_process'], 20);

            return ApiResponse::success($teachers);
        }catch(\Exception $e)
        {
            ApiResponse::error($e->getMessage(), 500);
        }
    }
}
