<?php
namespace Modules\Thesis\Contracts;

use App\Models\Academic\Thesis\ThesisTitle;

interface ThesisTitleServiceInterface
{
    /**
     * Crea un título de tesis.
     *
     * @param array $data Los datos del título de tesis.
     * @return ThesisTitle El título de tesis creado.
     */
    public function createThesisTitle(array $data, string|array $categoriesIds = null): ThesisTitle;
}
