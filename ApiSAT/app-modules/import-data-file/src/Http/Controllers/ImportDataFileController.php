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
        $request->validate([
            'file' => 'required|file|mimes:pdf'
            ]);
        try {

            $file = $request->file('file');
            $this->importDataFileService->importDataPdfThesis($file, $request->user()->id);
            return ApiResponse::success('Archivo procesado correctamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function importDataFileRequirementStudent(Request $request) : \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'file' => 'required|file|mimes:docx',
            'requirementStudentId' => 'required|exists:student_requirements,student_requirements_id',
        ]);


        try {

            $file = $request->file('file');
            $this->importDataFileService->importDataPdfRequirementStudent($file, $request->user()->id, $request->requirementStudentId);
            return ApiResponse::success(null, 'Archivo procesado para su revisión', 201 );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function importDataFilePreRequirementStudent(Request $request) : \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'file' => 'required|file|mimes:pdf',
            'requirementStudentId' => 'required|exists:student_prerequirements,student_prerequirements_id',
        ]);
        try {

            $file = $request->file('file');
            $this->importDataFileService->importDataPdfPreRequirementStudent($file, $request->user()->id, $request->requirementStudentId);
            return ApiResponse::success(null, 'Archivo procesado para su revisión', 201 );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
