<?php

use Illuminate\Support\Facades\Route;
use Modules\ThesisProcessStudent\Http\Controllers\StudentPhaseController;
use Modules\ThesisProcessStudent\Http\Controllers\StudentProcessController;

Route::prefix('thesis-process-student')->middleware(['auth:api','role:Estudiante-tesis'])
->group(function () {
    Route::get('get-data', [StudentPhaseController::class, 'getProcessAllData']);

    Route::get('get-data-dashboard', [StudentProcessController::class, 'getProcessDataDashboard']);
});
