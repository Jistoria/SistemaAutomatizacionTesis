<?php

namespace Modules\ThesisProcessStudent\Models;

use App\Models\Academic\Thesis\ThesisProcessPhases;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isEmpty;

class ThesisProcessPhaseStudent extends ThesisProcessPhases
{

    public function checkRequerimentsPhase() : bool
    {
        $requeriments = $this->phase->requirements;
        if (isEmpty($requeriments)) {
            return true;
        }
        return false;
    }

    public function checkBeforeRegister () : bool
    {
        $phase = $this->phase;
        $phases = $this->thesisProcess->phases;
        $phases = $phases->where('thesis_phases_id', $phase->thesis_phases_id);
        if (isEmpty($phases)) {
            return true;
        }
        return false;
    }

    public function checkBeforeAproved () : bool
    {
        $phase = $this->phase;
        $phases = $this->thesisProcess->phases;
        $phases = $phases->where('thesis_phases_id', $phase->thesis_phases_id);
        if (isEmpty($phases)) {
            return true;
        }
        return false;
    }




}
