<?php

namespace Modules\AnalystDegree\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Utils\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function changeStatusPrerequeriments(Request $request)
    {
        $allowedStatesforTeacher = State::getStateforTeacher();

        $request->validate([
            'status' => ['required', Rule::in($allowedStatesforTeacher)],
        ]);
        try {
            $statusEnum = State::toEnum($request->input('status'));
            $prerequeriments = $this->analystDegreeService->changeStatusPrerequeriments(
                $request->user()->id,
                $request->route('student_prerequirements_id'),
                $statusEnum );
            return ApiResponse::success('Estado de los prerequisitos actualizado correctamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }
    }
}
