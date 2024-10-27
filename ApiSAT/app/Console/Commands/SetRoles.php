<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-roles';

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
        $roles = [
            'Administrador-tesis',
            'Estudiante-tesis',
            'Docente-tesis',
            'Jurado-tesis',
            'Secretaria-tesis',
            'Decana'
        ];

        $this->info('Creando roles...');
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create([
                'name' => $role,
            ]);
        }
        $this->info('Roles creados correctamente');
    }
}
