<?php

namespace Modules\RequestPhases\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\RequestPhases\Http\Requests\RequestPhases;
use Modules\RequestPhases\Services\RequestPhasesService;

class RequestPhasesController
{


    public function __construct(
        protected RequestPhasesService $requestPhasesService
    )
    {

    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(RequestPhases $request)
    {
        try{
            $phaseRequest = $this->requestPhasesService->create($request->user(),$request->all());
            return ApiResponse::success($phaseRequest, 'Fase solicitada correctamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function approveAll(Request $request)
    {
        try{
            $this->requestPhasesService->approveAll($request->user());
            return ApiResponse::success(null, 'Fases aprobadas correctamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
