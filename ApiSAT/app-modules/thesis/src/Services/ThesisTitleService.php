<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisTitle;
use Illuminate\Support\Facades\Log;
use Modules\Thesis\Contracts\ThesisTitleServiceInterface;

class ThesisTitleService implements ThesisTitleServiceInterface
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
    public function createThesisTitle(array $data, string|array $categoriesIds = null ): ThesisTitle
    {
        $thesisTitle = $this->thesisTitle->create($data);
        if ($categoriesIds) {
            $thesisTitle->categoryAreas()->attach((array)$categoriesIds);
        }

        return $thesisTitle;
    }
}
