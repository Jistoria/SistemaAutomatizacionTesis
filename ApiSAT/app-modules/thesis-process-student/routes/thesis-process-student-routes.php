<?php

use Illuminate\Support\Facades\Route;
use Modules\ThesisProcessStudent\Http\Controllers\StudentPhaseController;
use Modules\ThesisProcessStudent\Http\Controllers\StudentProcessController;

Route::prefix('thesis-process-student')->middleware(['auth:api','role:Estudiante-tesis'])
->group(function () {
    Route::get('thesis-process', [StudentProcessController::class, 'getThesisProcess']);

    Route::get('thesis-phases-student', [StudentProcessController::class, 'getThesisPhasesStudent']);

    Route::get('dashboard', [StudentProcessController::class, 'getDashboard']);

    Route::get('requirements-phase/{phasesProcessPhaseId}', [StudentProcessController::class, 'getRequirementsPhase']);

    Route::get('download-resources', [StudentProcessController::class, 'downloadStudentResources']);


    Route::get('next-phase', [StudentProcessController::class, 'nextPhaseStudent']);

    Route::post('request-next-phase', [StudentProcessController::class, 'requestNextPhase']);


});
