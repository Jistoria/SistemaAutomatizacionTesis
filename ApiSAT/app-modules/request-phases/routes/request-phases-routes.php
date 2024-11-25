<?php

// use Modules\RequestPhases\Http\Controllers\RequestPhasesController;

// Route::get('/request-phases', [RequestPhasesController::class, 'index'])->name('request-phases.index');
// Route::get('/request-phases/create', [RequestPhasesController::class, 'create'])->name('request-phases.create');
// Route::post('/request-phases', [RequestPhasesController::class, 'store'])->name('request-phases.store');
// Route::get('/request-phases/{request-phase}', [RequestPhasesController::class, 'show'])->name('request-phases.show');
// Route::get('/request-phases/{request-phase}/edit', [RequestPhasesController::class, 'edit'])->name('request-phases.edit');
// Route::put('/request-phases/{request-phase}', [RequestPhasesController::class, 'update'])->name('request-phases.update');
// Route::delete('/request-phases/{request-phase}', [RequestPhasesController::class, 'destroy'])->name('request-phases.destroy');

use Illuminate\Support\Facades\Route;
use Modules\RequestPhases\Http\Controllers\RequestPhasesController;

Route::post('/request-phases', [RequestPhasesController::class, 'store'])->name('request-phases.store');
