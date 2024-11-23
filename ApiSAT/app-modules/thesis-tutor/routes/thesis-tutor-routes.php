<?php

// use Modules\ThesisTutor\Http\Controllers\ThesisTutorController;

use Illuminate\Support\Facades\Route;
use Modules\ThesisTutor\Http\Controllers\ThesisTutorController;

Route::prefix('thesis-tutor')->middleware(['auth:api', 'role:Docente-tesis'])->group(function () {

    Route::get('my-students', [ThesisTutorController::class, 'myStudents']);

    Route::get('requirements-student/{student_id}', [ThesisTutorController::class, 'requirementsStudent'])->middleware('ensureIsStudentTutor');

    Route::put('requirements-student/change-status/{student_id}/{student_requirements_id}', [ThesisTutorController::class, 'changeStatusRequirementStudent'])->middleware('ensureIsStudentTutor');

    Route::get('observations-requirements/{student_id}/{student_requirements_id}', [ThesisTutorController::class, 'getObservationsRequirement'])->middleware('ensureIsStudentTutor');
    Route::post('observations-requirements/{student_id}', [ThesisTutorController::class, 'storeObservationsRequirement'])->middleware('ensureIsStudentTutor');
    Route::delete('observations-requirements/{student_id}/{observation_requirement_id}', [ThesisTutorController::class, 'deleteObservationsRequirement'])->middleware('ensureIsStudentTutor');
});
