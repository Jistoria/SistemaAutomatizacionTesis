<?php

namespace Modules\ImportDataFile\Services;

use Faker\Core\Uuid;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\ImportDataFile\Jobs\ProcessPdfThesisData;
use \Illuminate\Http\UploadedFile;

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

    /**
     * Importa un archivo PDF de tesis y despacha un trabajo para procesar los datos del PDF.
     *
     * @param UploadedFile $file El archivo PDF subido.
     * @param string $id El identificador de la tesis.
     *
     * @return void
     */
    public function importDataPdfThesis(UploadedFile $file, string $id) : void
    {
        // Guardar el archivo en el almacenamiento temporal
        $filePath = $file->storeAs('pdfs', $file->getClientOriginalName(), 'public');

        // Despachar el Job para procesar el PDF
        ProcessPdfThesisData::dispatch(Storage::disk('public')->path($filePath), $id);
    }

}
