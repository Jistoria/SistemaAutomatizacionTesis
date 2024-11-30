<?php

namespace Modules\ThesisProcessStudent\Models;

use App\Models\Academic\Thesis\ThesisPhase;
use App\Models\Academic\Thesis\ThesisProcess;
use App\Utils\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ThesisProcessStudent extends ThesisProcess
{

    public function getStudentPhasesGroupedByModule()
    {
        return $this->phasesStudent()
            ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->join('thesis_modules', 'thesis_phases.thesis_module_id', '=', 'thesis_modules.thesis_module_id')
            ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->leftJoin('student_requirements', 'student_requirements.thesis_process_phases_id', '=', 'thesis_process_phases.thesis_process_phases_id')
            ->select(
                'thesis_modules.name as module_name','thesis_phases.name as phase_name',
                'order_phases_thesis.order',
                'thesis_process_phases.thesis_process_phases_id',
                'thesis_process_phases.approval', 'thesis_process_phases.state_now',
                'thesis_process_phases.date_start', 'thesis_process_phases.date_end',
                DB::raw('AVG(student_requirements.approved::int) * 100 as progress'))
            ->where('thesis_process_phases.thesis_process_id', $this->thesis_process_id)
            ->groupBy(
                'thesis_modules.name',
                'thesis_phases.name',
                'thesis_modules.order',
                'order_phases_thesis.order',
                'thesis_process_phases.thesis_process_phases_id',
                'thesis_process_phases.approval',
                'thesis_process_phases.state_now',
                'thesis_process_phases.date_start',
                'thesis_process_phases.date_end'
            )
            ->orderBy('thesis_modules.order')
            ->orderBy('order_phases_thesis.order')
            ->get()
            ->groupBy('module_name');
    }

    public function nextPhaseStudent()
{
    $lastesAproved = $this->phasesStudent()
        ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
        ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=','thesis_process_phases.thesis_phases_id')
        ->select('thesis_phases.name', 'order_phases_thesis.next_phases_id', 'thesis_process_phases.student_id', 'thesis_process_phases.date_end')
        ->where('approval', true)
        ->orderBy('date_end', 'desc')
        ->first();


    if (!$lastesAproved) {
        return null; // Manejar el caso donde no hay fases aprobadas
    }

    $nextPhase = ThesisPhase::join('thesis_modules', 'thesis_phases.thesis_module_id', '=', 'thesis_modules.thesis_module_id')
        ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
        ->leftJoin('pre_requirements', 'pre_requirements.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
        ->leftJoin('student_prerequirements', 'student_prerequirements.pre_requirements_id', '=', 'pre_requirements.pre_requirements_id')
        ->select(
            'thesis_phases.thesis_phases_id',
            'thesis_modules.name as module_name',
            'thesis_phases.name as phase_name',
            DB::raw('
            CASE
                WHEN COUNT(pre_requirements.pre_requirements_id) > 0 THEN
                    JSON_AGG(
                        JSON_BUILD_OBJECT(
                            \'pre_requirements_id\', pre_requirements.pre_requirements_id,
                            \'name\', COALESCE(pre_requirements.name, \'Sin nombre\'),
                            \'description\', COALESCE(pre_requirements.description, \'Sin descripción\'),
                            \'type\', COALESCE(pre_requirements.type, \'General\'),
                            \'extension\', COALESCE(pre_requirements.extension, \'Sin extensión\'),
                            \'url_resource\', COALESCE(pre_requirements.url_resource, \'Sin recurso\'),
                            \'approval_role\', COALESCE(pre_requirements.approval_role, \'Sin rol de aprobación\'),
                            \'status\', COALESCE(student_prerequirements.status, \''.State::PENDING.'\'),
                            \'observation\', COALESCE(student_prerequirements.observation, \'Sin observación\'),
                            \'approved\', COALESCE(student_prerequirements.approved, false),
                            \'approved_date\', COALESCE(student_prerequirements.approved_date, null),
                            \'approved_by_user\', COALESCE(student_prerequirements.approved_by_user, null),
                            \'url_file\', COALESCE(student_prerequirements.url_file, null)
                        )
                    )
                ELSE NULL
            END as pre_requirements
        '),
            DB::raw('(COUNT(pre_requirements.pre_requirements_id) = SUM(CASE WHEN student_prerequirements.approved = true THEN 1 ELSE 0 END)) AS all_requirements_met'),
            DB::raw('EXISTS (SELECT 1 FROM phase_requests WHERE phase_requests.requested_phase_id = ? AND phase_requests.student_id = ?) as phase_requests')
        )
        ->where('thesis_phases.thesis_phases_id', $lastesAproved->next_phases_id)
        ->groupBy('thesis_phases.thesis_phases_id', 'thesis_modules.name', 'thesis_phases.name')
        ->setBindings([$lastesAproved->next_phases_id, $lastesAproved->student_id], 'select') // Asignar el valor escapado a la consulta
        ->get();

        $nextPhase->transform(function ($item) {
            $item->pre_requirements = json_decode($item->pre_requirements, true);
            return $item;
        });


    return $nextPhase->first();
}





}
