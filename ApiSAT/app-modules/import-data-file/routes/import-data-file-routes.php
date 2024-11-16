<?php

use Illuminate\Support\Facades\Route;
use Modules\ImportDataFile\Http\Controllers\ImportDataFileController;


Route::prefix('import-data-file')->group(function () {
    Route::post('pdf-thesis', [ImportDataFileController::class, 'importDataFile'])->middleware(['auth:api', 'role:Administrador-tesis']);

    Route::post('document-requirement-student', [ImportDataFileController::class, 'importDataFileRequirementStudent'])->middleware(['auth:api', 'role:Estudiante-tesis']);

    Route::get('name-documents-phase', [ImportDataFileController::class, 'nameDocumentsPhase'])->middleware(['auth:api', 'role:Administrador-tesis']);
});
