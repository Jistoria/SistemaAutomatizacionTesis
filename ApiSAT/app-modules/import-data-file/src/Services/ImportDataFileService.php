<?php

namespace Modules\ImportDataFile\Services;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\ImportDataFile\Jobs\ProcessPdfThesisData;

class ImportDataFileService
{
    public function __construct(
        // protected User $user,
        // protected Student $student,
        // protected Teacher $teacher,
        // protected ThesisTitle $thesisTitle
        // protected
    )
    {}

    public function importDataPdfThesis($file, $id) : void
    {
        // Guardar el archivo en el almacenamiento temporal
        $filePath = $file->storeAs('pdfs', $file->getClientOriginalName(), 'public');

        // Despachar el Job para procesar el PDF
        ProcessPdfThesisData::dispatch(Storage::disk('public')->path($filePath), $id);
    }

}
