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
            ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->select('thesis_phases.name', 'order_phases_thesis.next_phases_id')
            ->where('approval', true)
            ->orderBy('date_end', 'desc')
            ->first();


        $nextPhase = ThesisPhase::join('thesis_modules', 'thesis_phases.thesis_module_id', '=', 'thesis_modules.thesis_module_id')
        ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
        ->leftJoin('pre_requirements', 'pre_requirements.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
        ->select('thesis_phases.thesis_phases_id','thesis_modules.name as module_name','thesis_phases.name as phase_name')
        ->where('thesis_phases.thesis_phases_id', $lastesAproved->next_phases_id)
        ->groupBy('thesis_phases.thesis_phases_id','thesis_modules.name','thesis_phases.name')
        ->first();

        return $nextPhase;

    }




}
