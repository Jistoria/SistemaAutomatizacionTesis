<?php

namespace Modules\RequestPhases\Services;

use App\Models\Academic\Thesis\Requests\PhaseRequest;
use App\Models\Auth\User;
use App\Utils\State;

class RequestPhasesService
{
    public function __construct(
        protected PhaseRequest $phaseRequest
    )
    {}

    public function create(User $dataStudent, array $data): PhaseRequest
    {
        $message = $this->messageRequest($dataStudent->name, $data['phase_name']);



        $phaseRequest = $this->phaseRequest->create([
            'thesis_process_id' => $data['thesis_process_id'],
            'student_id' => $dataStudent->id,
            'requested_phase_id' => $data['requested_phase_id'],
            'comments' => $message,
            'request_date' => now(),
            'state' => State::SENT,
        ]);

        return $phaseRequest;
    }


    protected function messageRequest(string $name, string $phase) : string
    {
        return "El estudiante $name ha solicitado la fase $phase";
    }



}
