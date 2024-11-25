<?php

namespace Modules\ThesisProcessStudent\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ThesisProcessStudent\Services\RequirementsStudentService;
use Modules\ThesisProcessStudent\Services\ThesisProcessStudentService;

class StudentProcessController
{

    public function __construct(
        protected ThesisProcessStudentService $studentPhaseService,
        protected RequirementsStudentService $requirementsStudentService

    )
    {}

    public function getThesisProcess(Request $request)
    {
        try{
            $processPhase = $this->studentPhaseService->findThesisProcessById($request->user()->id);
            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function getThesisPhasesStudent(Request $request)
    {
        try{
            $processPhase = $this->studentPhaseService->phasesGroupByModule($request->user()->id);
            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function getDashboard(Request $request)
    {
        try{
            $processPhase = $this->studentPhaseService->dataDashboard($request->user()->id);
            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function getRequirementsPhase($phasesProcessPhaseId)
    {
        try{
            $processPhase = $this->requirementsStudentService->requirementsPhaseStudent($phasesProcessPhaseId);
            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function downloadStudentResources()
    {
        try {
            // Directorio donde están almacenados los recursos
            $directory = "anexos-system/student-resources";

            // Crea el ZIP
            $zipPath = app(ImportDataFileServiceInterface::class)->downloadResourcesZip(public_path($directory));

            // Descarga el archivo
            return response()->download($zipPath, basename($zipPath))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function nextPhaseStudent(Request $request)
    {
        try{
            $processPhase = $this->studentPhaseService->nextPhaseStudent($request->user()->id);
            return ApiResponse::success($processPhase);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }
    }



}
