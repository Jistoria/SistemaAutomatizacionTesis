<?php

use Illuminate\Support\Facades\Route;
use Modules\AnalystDegree\Http\Controllers\AnalystDegreeController;

Route::prefix('analyst-degree')->middleware(['auth:api', 'role:Analista-Carrera'])->group(function () {

    Route::get('students', [AnalystDegreeController::class, 'students']);

    Route::get('students-details/{student_id}', [AnalystDegreeController::class, 'studentsDetails']);
});
