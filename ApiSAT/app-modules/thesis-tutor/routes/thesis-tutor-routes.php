<?php

// use Modules\ThesisTutor\Http\Controllers\ThesisTutorController;

use Illuminate\Support\Facades\Route;
use Modules\ThesisTutor\Http\Controllers\ThesisTutorController;

Route::prefix('thesis-tutor')->middleware(['auth:api', 'role:Docente-tesis'])->group(function () {
    Route::get('my-students', [ThesisTutorController::class, 'myStudents']);
});
