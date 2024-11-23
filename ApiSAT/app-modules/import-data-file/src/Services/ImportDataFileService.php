<?php

namespace Modules\ImportDataFile\Services;

use Composer\Util\Zip;
use Faker\Core\Uuid;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\ImportDataFile\Jobs\ProcessPdfThesisData;
use \Illuminate\Http\UploadedFile;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;
use ZipArchive;

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

    public function downloadResourcesZip(string $directory): string
{
    // Archivos del estudiante en el directorio
    $files = File::files($directory);

    // Nombre del archivo ZIP
    $zipFileName = "recursos_estudiante.zip";

    // Ruta temporal para almacenar el archivo ZIP
    $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;

    // Elimina el archivo ZIP si ya existe
    if (file_exists($tempPath)) {
        unlink($tempPath);
    }

    // Crear el archivo ZIP
    $zip = new ZipArchive;
    if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($files as $file) {
            // Verificar si el archivo realmente existe antes de añadirlo al ZIP
            if (!file_exists($file->getPathname())) {
                throw new \Exception("El archivo no existe: {$file->getPathname()}");
            }

            // Añadir el archivo al ZIP
            $zip->addFile($file->getPathname(), $file->getBasename());
        }
        $zip->close();
    } else {
        throw new \Exception('No se pudo crear el archivo ZIP');
    }

    return $tempPath; // Retorna la ruta del archivo ZIP
}


}
