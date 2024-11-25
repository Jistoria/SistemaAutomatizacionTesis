<?php

namespace Modules\RequestPhases\Services;

use App\Models\Academic\Thesis\Requests\PhaseRequest;
use App\Utils\State;

class RequestPhasesService
{
    public function __construct(
        protected PhaseRequest $phaseRequest
    )
    {}

    public function create(array $dataStudent, array $data): PhaseRequest
    {

        // $message = $this->messageRequest();

        $phaseRequest = $this->phaseRequest->create([
            'student_id' => $dataStudent['id'],
            'requested_phase_id' => $data,
            'request_date' => now(),
            'state' => State::SENT,
        ]);

        return $phaseRequest;
    }


    protected function messageRequest(string $name, string $phase): string
    {


        return '';
    }



}
