<?php

namespace Modules\ThesisProcessStudent\Models;

use App\Models\Academic\Thesis\ThesisProcess;
use Illuminate\Database\Eloquent\Model;

class ThesisProcessStudent extends ThesisProcess
{

    public function getStudentPhasesGroupedByModule()
    {
        return $this->phasesStudent()
            ->join('thesis_phases', 'thesis_process_phases.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->join('thesis_modules', 'thesis_phases.thesis_module_id', '=', 'thesis_modules.thesis_module_id')
            ->join('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->select('thesis_modules.name as module_name', 'thesis_phases.name as phase_name', 'order_phases_thesis.order', 'thesis_process_phases.approval')
            ->where('thesis_process_phases.thesis_process_id', $this->thesis_process_id)
            ->orderBy('thesis_modules.order')
            ->orderBy('order_phases_thesis.order')
            ->get()
            ->groupBy('module_name');
    }

}
