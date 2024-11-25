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
}
