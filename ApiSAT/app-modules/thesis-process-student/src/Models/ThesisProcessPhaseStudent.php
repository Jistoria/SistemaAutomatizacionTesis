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

}
