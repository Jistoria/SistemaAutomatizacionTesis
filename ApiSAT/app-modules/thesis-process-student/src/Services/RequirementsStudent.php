<?php

namespace Modules\ThesisProcessStudent\Services;

use Modules\ThesisProcessStudent\Models\Requirements;

class RequirementsStudent
{
    public function __construct(
        protected Requirements $requirements
    )
    {}


}
