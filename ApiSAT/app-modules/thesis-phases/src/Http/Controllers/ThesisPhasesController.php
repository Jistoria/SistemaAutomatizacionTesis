<?php

namespace Modules\ThesisPhases\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ThesisPhases\Services\ThesisPhasesService;

class ThesisPhasesController
{
    public function __construct(
        protected ThesisPhasesService $thesisPhasesService
    )
    {}

    public function pluck(Request $request)
    {
        try {
            return ApiResponse::success($this->thesisPhasesService->pluck());
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }


    public function dataDashboard()
    {
        try {
            $data = $this->thesisPhasesService->dataDashboard();
            return ApiResponse::success($data);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

}
