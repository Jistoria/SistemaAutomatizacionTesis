<?php

namespace Modules\ImportDataFile\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\ImportDataFile\Services\ImportDataFileService;

class ImportDataFileController
{
    public function __construct(
        protected ImportDataFileService $importDataFileService
    )
    {}



    public function importDataFile(Request $request) : \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
            'file' => 'required|file|mimes:pdf'
            ]);
            $file = $request->file('file');
            $this->importDataFileService->importDataPdfThesis($file, $request->user()->id);
            return ApiResponse::success('Archivo procesado correctamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
