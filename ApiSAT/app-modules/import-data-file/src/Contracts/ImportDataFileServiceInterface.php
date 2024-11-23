<?php

namespace Modules\ImportDataFile\Contracts;

use Illuminate\Http\UploadedFile;

interface ImportDataFileServiceInterface
{
    /**
     * Importa un archivo PDF de tesis y despacha un trabajo para procesar los datos del PDF.
     *
     * @param UploadedFile $file El archivo PDF subido.
     * @param string $id El identificador de la tesis.
     *
     * @return void
     */
    public function importDataPdfThesis(UploadedFile $file, string $id) : void;

    public function importDataPdfRequirementStudent(UploadedFile $file, string $userId, string $requirementStudentId) : void;

    public function deleteFile(string $path) : void;
}
