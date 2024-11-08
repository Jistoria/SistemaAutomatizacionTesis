<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisTitle;

class ThesisService
{
    public function __construct(
        protected ThesisTitle $thesis
    )
    {}

}
