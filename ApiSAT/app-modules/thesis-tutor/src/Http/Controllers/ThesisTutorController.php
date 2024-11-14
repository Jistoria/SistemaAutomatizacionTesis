<?php

namespace Modules\ThesisTutor\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ThesisTutor\Services\ThesisTutorService;

class ThesisTutorController
{
    public function __construct(
        private ThesisTutorService $thesisTutorService
    )
    {}

    public function myStudents(Request $request)
    {
        $students = $this->thesisTutorService->getMyStudents($request->user()->id);

        return response()->json($students);
    }
}
