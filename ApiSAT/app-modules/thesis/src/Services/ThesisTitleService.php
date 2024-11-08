<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisTitle;

class ThesisTitleService
{
    public function __construct(
        protected ThesisTitle $thesisTitle
    )
    {}

    /**
     * Crea un título de tesis.
     *
     * @param array $data Los datos del título de tesis.
     * @return ThesisTitle El título de tesis creado.
     */
    public function createThesisTitle(array $data): ThesisTitle
    {
        return $this->thesisTitle->firstOrCreate(['title' => $data['title']], $data);
    }
}
