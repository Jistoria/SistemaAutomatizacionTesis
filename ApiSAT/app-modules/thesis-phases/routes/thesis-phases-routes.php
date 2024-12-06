<?php

use Illuminate\Support\Facades\Route;
use Modules\ThesisPhases\Http\Controllers\ThesisPhasesController;

// Route::get('/thesis-phases', [ThesisPhasesController::class, 'index'])->name('thesis-phases.index');
// Route::get('/thesis-phases/create', [ThesisPhasesController::class, 'create'])->name('thesis-phases.create');
// Route::post('/thesis-phases', [ThesisPhasesController::class, 'store'])->name('thesis-phases.store');
// Route::get('/thesis-phases/{thesis-phase}', [ThesisPhasesController::class, 'show'])->name('thesis-phases.show');
// Route::get('/thesis-phases/{thesis-phase}/edit', [ThesisPhasesController::class, 'edit'])->name('thesis-phases.edit');
// Route::put('/thesis-phases/{thesis-phase}', [ThesisPhasesController::class, 'update'])->name('thesis-phases.update');
// Route::delete('/thesis-phases/{thesis-phase}', [ThesisPhasesController::class, 'destroy'])->name('thesis-phases.destroy');

Route::get('/thesis-phases-pluck', [ThesisPhasesController::class, 'pluck'])->name('thesis-phases.pluck');

Route::get('/thesis-phases-dashboard', [ThesisPhasesController::class, 'dataDashboard'])->name('thesis-phases.dataDashboard');
