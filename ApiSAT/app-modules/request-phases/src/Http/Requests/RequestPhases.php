<?php

namespace Modules\RequestPhases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPhases extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

public function rules(): array
{
    return [
        'thesis_process_id' => 'required|exists:thesis_processes,thesis_process_id',
        'student_id' => 'required|exists:students,id',
        'requested_phase_id' => 'required|exists:thesis_phases,thesis_phases_id',
    ];
}
}
