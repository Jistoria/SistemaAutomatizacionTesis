<?php

namespace App\Console\Commands;

use App\Models\Academic\Thesis\Requirement\PreRequirements;
use App\Models\Academic\Thesis\Requirement\Requirement;
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
            ['name' => 'Desarrollo', 'order' => 2,
                'phases' => [
                    ['name' => 'Fase Diseño', 'order' => 1, 'requirements' => [
                        ['name' => 'PAT-04-F-004', 'type' => 'document', 'extension' => 'docx'],
                        ['name' => 'Trabajo Escrito', 'type' => 'document', 'extension' => 'docx']
                        ]],
                    ['name' => 'Fase Resultado', 'order' => 2, 'requirements' => [
                        ['name' => 'Informe de Similitud', 'type' => 'document', 'extension' => 'docx'],
                        ['name' => 'Registro de tutorías de titulación', 'type' => 'document', 'extension' => 'docx']
                        ]
                    ]
                    ]
            ],
            ['name' => 'Evaluación', 'order' => 3, 'phases' => [
                    ['name' => 'Fase Evaluación', 'order' => 1,
                    'pre_requirements' => [
                        ['name' => 'Documento Libreria', 'type' => 'document', 'extension' => 'pdf'],
                        ['name' => 'Certificado Ingles B1', 'type' => 'document', 'extension' => 'pdf']
                        ]
                    ]
                ]
            ],
        ];
        $previousPhaseOrder = null;
        foreach ($modulesAndPhases as $moduleData) {
            $module = new \App\Models\Academic\Thesis\Module([
                'name' => $moduleData['name'],
                'order' => $moduleData['order'],
                'created_by_user' => $adminId->id,
                'updated_by_user' => $adminId->id
            ]);

            $module->save();

            if (isset($moduleData['phases'])) {
                $this->info('Creando fases para el módulo ' . $moduleData['name']);



                foreach ($moduleData['phases'] as $phaseData) {
                    $this->info('Creando fase ' . $phaseData['name']);

                    $phase = new \App\Models\Academic\Thesis\ThesisPhase([
                        'name' => $phaseData['name'],
                        'thesis_module_id' => $module->thesis_module_id,
                        'created_by_user' => $adminId->id,
                        'updated_by_user' => $adminId->id
                    ]);

                    $phase->save();

                    $currentPhaseOrder = $phase->order()->create([
                        'order' => $phaseData['order'],
                        'previous_phases_id' => $previousPhaseOrder ? $previousPhaseOrder->thesis_phases_id : null,
                        'thesis_phases_id' => $phase->thesis_phases_id
                    ]);

                    $currentPhaseOrder->save();



                    if ($previousPhaseOrder) {
                        $this->info($previousPhaseOrder);
                        $previousPhaseOrder->next_phases_id = $phase->thesis_phases_id;
                        $previousPhaseOrder->save();
                    }

                    // Actualiza la fase previa a la actual
                    $previousPhaseOrder = $currentPhaseOrder;

                    if (isset($phaseData['requirements'])) {
                        $this->info('Creando requisitos para la fase ' . $phaseData['name']);

                        foreach ($phaseData['requirements'] as $requirementData) {
                            $requirement = new Requirement([
                                'name' => $requirementData['name'],
                                'type' => $requirementData['type'],
                                'extension' =>  $requirementData['extension'],
                                'description' => 'Documento que se debe entregar a lo largo de la fase',
                                'thesis_phases_id' => $phase->thesis_phases_id,
                                'approval_role' => 'Docente-tesis',
                                'created_by_user' => $adminId->id,
                                'updated_by_user' => $adminId->id
                            ]);
                            $requirement->save();
                        }
                    }

                    if(isset($phaseData['pre_requirements'])) {
                        $this->info('Creando requisitos previos para la fase ' . $phaseData['name']);

                        foreach ($phaseData['pre_requirements'] as $requirementData) {
                            $requirement = new PreRequirements([
                                'name' => $requirementData['name'],
                                'type' => $requirementData['type'],
                                'extension' =>  $requirementData['extension'],
                                'description' => 'Documento que se debe entregar antes de la fase',
                                'thesis_phases_id' => $phase->thesis_phases_id,
                                'approval_role' => 'Analista-Carrera',
                                'created_by_user' => $adminId->id,
                                'updated_by_user' => $adminId->id
                            ]);
                            $requirement->save();
                        }
                    }
                }
            }
        }
    }

}
