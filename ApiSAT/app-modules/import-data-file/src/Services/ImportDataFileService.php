<?php

namespace Modules\ImportDataFile\Services;

use Faker\Core\Uuid;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\ImportDataFile\Jobs\ProcessPdfThesisData;
use \Illuminate\Http\UploadedFile;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;

class ImportDataFileService implements ImportDataFileServiceInterface
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

    public function importDataPdfRequirementStudent(UploadedFile $file, string $userId, string $requirementStudentId) : void
    {
        // Guardar el archivo en el almacenamiento temporal
        $name_document = now()->format('Y-m-d_H-i-s').'_'.$file->getClientOriginalName();

        $filePath = $file->storeAs('pdfs-students/'.$userId, $name_document, 'public');

        app(RequirementsStudentServiceInterface::class)->updateDocumentRequirementStudent($requirementStudentId, $filePath);
    }

    public function deleteFile(string $path) : void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

}
