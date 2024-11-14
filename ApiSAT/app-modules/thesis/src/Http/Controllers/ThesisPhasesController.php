<?php

namespace Modules\Thesis\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\Thesis\Services\ThesisPhasesService;

class ThesisPhasesController
{
    public function __construct(
        protected ThesisPhasesService $thesisPhasesService
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
}
