<?php
namespace Modules\Thesis\Contracts;

use App\Models\Academic\Thesis\ThesisProcess;

interface ThesisProcessServiceInterface
{
    /**
     * Crea o devuelve el primer proceso de tesis que coincida con los datos proporcionados.
     *
     * @param array $data Datos necesarios para crear o buscar el proceso de tesis.
     * @param string $userId ID del usuario que esta haciendo la creacion/consulta.
     * @return ThesisProcess El proceso de tesis creado o encontrado.
     */
    public function firstOrCreateThesisProcess(array $data, string $userId): ThesisProcess;
}
