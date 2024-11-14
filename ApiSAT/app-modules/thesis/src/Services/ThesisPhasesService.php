<?php

namespace Modules\Thesis\Services;

use App\Models\Academic\Thesis\ThesisPhase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;

class ThesisPhasesService implements ThesisPhasesServiceInterface
{
    public function __construct(
        protected ThesisPhase $thesisPhase
    )
    {}

    public function getThesisPhase(string $thesisPhaseId): ThesisPhase
    {
        return $this->thesisPhase->find($thesisPhaseId);
    }

    public function getThesisPhaseByName(string $name): ThesisPhase
    {
        return $this->thesisPhase->where('name', $name)->first();
    }

    public function getThesisPhaseByOrder(int $orderPhase, int $orderModule): ?ThesisPhase
    {
        $thesisPhase = $this->thesisPhase->whereHas('module', function ($query) use ($orderModule) {
            $query->where('order', $orderModule);
        })->whereHas('order', function ($query) use ($orderPhase) {
            $query->where('order', $orderPhase);
        })->first();

        if (!$thesisPhase) {
            Log::info("No se encontró ninguna fase con el módulo de orden $orderModule y fase de orden $orderPhase");
        }

        return $thesisPhase;
    }


    public function getPhasesModule(): Collection
    {
        return $this->thesisPhase
            ->rightJoin('thesis_modules', 'thesis_phases.thesis_module_id', '=', 'thesis_modules.thesis_module_id')
            ->leftJoin('order_phases_thesis', 'order_phases_thesis.thesis_phases_id', '=', 'thesis_phases.thesis_phases_id')
            ->select(
                'thesis_modules.name as module_name',
                'thesis_modules.order as module_order',
                'thesis_phases.name as phase_name',
                'order_phases_thesis.order as phase_order'
            )
            ->orderBy('module_order')
            ->orderBy('phase_order')
            ->get()
            ->groupBy('module_name');
    }


}
