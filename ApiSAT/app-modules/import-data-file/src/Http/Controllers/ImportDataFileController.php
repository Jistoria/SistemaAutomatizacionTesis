<?php

namespace Modules\ImportDataFile\Http\Controllers;

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

            return response()->json([
            'message' => 'Se está procesando el archivo, por favor espere unos minutos'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
            'error' => 'Ocurrió un error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
}
