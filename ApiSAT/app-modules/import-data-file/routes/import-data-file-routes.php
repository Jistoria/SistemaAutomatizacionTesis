<?php

use Illuminate\Support\Facades\Route;
use Modules\ImportDataFile\Http\Controllers\ImportDataFileController;

Route::post('import-data-file', [ImportDataFileController::class, 'importDataFile']);
