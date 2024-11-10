<?php

use Illuminate\Support\Facades\Route;
use Modules\ThesisProcessStudent\Http\Controllers\StudentPhaseController;

Route::prefix('thesis-process-student')->middleware(['auth:api','role:Estudiante-tesis'])
->group(function () {
    Route::get('get-data', [StudentPhaseController::class, 'getProcessAllData']);

    Route::get('get-data-dashboard', [StudentPhaseController::class, 'getProcessDataDashboard']);
});
