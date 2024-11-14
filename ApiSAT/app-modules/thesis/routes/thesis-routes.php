<?php

// use Modules\Thesis\Http\Controllers\ThesisController;

// Route::get('/theses', [ThesisController::class, 'index'])->name('theses.index');
// Route::get('/theses/create', [ThesisController::class, 'create'])->name('theses.create');
// Route::post('/theses', [ThesisController::class, 'store'])->name('theses.store');
// Route::get('/theses/{thesi}', [ThesisController::class, 'show'])->name('theses.show');
// Route::get('/theses/{thesi}/edit', [ThesisController::class, 'edit'])->name('theses.edit');
// Route::put('/theses/{thesi}', [ThesisController::class, 'update'])->name('theses.update');
// Route::delete('/theses/{thesi}', [ThesisController::class, 'destroy'])->name('theses.destroy');

use Illuminate\Support\Facades\Route;
use Modules\Thesis\Http\Controllers\ThesisPhasesController;

Route::prefix('thesis')->group(function () {
    Route::get('phases-module', [ThesisPhasesController::class, 'phasesModule'])->middleware(['auth:api']);
});


