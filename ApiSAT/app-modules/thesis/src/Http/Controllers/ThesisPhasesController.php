<?php

namespace Modules\Thesis\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\Thesis\Services\ThesisPhasesService;
use Modules\Thesis\Services\ThesisProcessService;

class ThesisPhasesController
{
    public function __construct(
        protected ThesisPhasesService $thesisPhasesService,
        protected ThesisProcessService $thesisProcessService
    )
    {}

    public function phasesModule()
    {
        try{
            $phases = $this->thesisPhasesService->getPhasesModule();
            return ApiResponse::success($phases);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    public function processThesis(Request $request)
    {
        try{
            $phases = $this->thesisProcessService->getPaginatedThesisProcessesWithRelations(20);
            return ApiResponse::success($phases);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
}
