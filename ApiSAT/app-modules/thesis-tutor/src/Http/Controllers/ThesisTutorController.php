<?php

namespace Modules\ThesisTutor\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Utils\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            $students = $this->thesisTutorService->getMyStudents(
                $request->user()->id,
                $request->input('pagination'),
                $request->input('search'),
                $request->input('filter')
            );
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
        $allowedStates = State::getAllStates();

        $request->validate([
            'status' => ['required', Rule::in($allowedStates)],
        ]);
        try{
            $statusEnum = State::toEnum($request->input('status'));
            $this->thesisTutorService->changeStatusRequirementStudent(
                $request->user()->id,
                $request->route('student_requirements_id'),
                $statusEnum
            );
            return ApiResponse::success('status_changed');
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function storeObservationsRequirement(Request $request)
    {
        $request->validate([
            'student_requirements_id' => 'required|uuid|exists:student_requirements,student_requirements_id',
            'comment' => 'required|string'
        ]);
        try{
            $observation = $this->thesisTutorService->createObservationsRequirement(
                $request->user()->id,
                $request->except('student_id')
            );
            return ApiResponse::success(data: $observation, message: 'Observación creada correctamente', status: 201);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function getObservationsRequirement(Request $request)
    {
        try{
            $observations = $this->thesisTutorService->getObservationsRequirement(
                $request->route('student_requirements_id')
            );
            return ApiResponse::success($observations);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function deleteObservationsRequirement(Request $request)
    {
        try{
            $this->thesisTutorService->deleteObservationsRequirement(
                $request->route('observation_requirement_id')
            );
            return ApiResponse::success('Observación eliminada correctamente');
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
}
