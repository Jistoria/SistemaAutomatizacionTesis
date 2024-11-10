<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use Illuminate\Console\Command;

class SetModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminId = User::whereHas('roles', function ($query) {
            $query->where('name', 'Administrador-tesis');
        })->select('id')->first();

        $modulesAndPhases = [
            ['name' => 'Planificación', 'order' => 1, 'phases' => [['name' => 'Fase de Planificación', 'order' => 1]]],
            ['name' => 'Desarrollo', 'order' => 2, 'phases' => [['name' => 'Fase Diseño', 'order' => 1], ['name' => 'Fase Resultado', 'order' => 2]]],
            ['name' => 'Evaluación', 'order' => 3],
        ];

        foreach ($modulesAndPhases as $moduleData) {
            // Crear el módulo
            $module = new \App\Models\Academic\Thesis\Module([
                'name' => $moduleData['name'],
                'order' => $moduleData['order'],
                'created_by_user' => $adminId->id,
                'updated_by_user' => $adminId->id
            ]);

            $module->save();

            // Verificar y crear las fases para el módulo
            if (isset($moduleData['phases'])) {
                $this->info('Creando fases para el modulo ' . $moduleData['name']);

                foreach ($moduleData['phases'] as $phaseData) {
                    $this->info('Creando fase ' . $phaseData['name']);

                    $phase = new \App\Models\Academic\Thesis\ThesisPhase([
                        'name' => $phaseData['name'],
                        'thesis_module_id' => $module->thesis_module_id,
                        'created_by_user' => $adminId->id,
                        'updated_by_user' => $adminId->id
                    ]);


                    $phase->save();

                    $order = $phase->order()->create([
                        'order' => $phaseData['order'],
                        'thesis_phases_id' => $phase->thesis_phases_id
                    ]);

                    $order->save();

                }
            }
        }
    }

}
