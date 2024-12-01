<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetAdminTesis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-admin-tesis';

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
        $this->info('Creando usuario administrador de tesis...');
        $user = \App\Models\Auth\User::create([
            'id' => \Ramsey\Uuid\Uuid::uuid4(),
            'name' => 'Admin Tesis',
            'email' => 'admin_tesis@uleam.edu.ec',
            'password' => 'admin_tesis',
        ]);
        $user->assignRole('Administrador-tesis');

        $this->info('Creando usuario analista de tesis...');
        $user = \App\Models\Auth\User::create([
            'id' => \Ramsey\Uuid\Uuid::uuid4(),
            'name' => 'Analista Tesis',
            'email' => 'analys_tesis@uleam.edu.ec',
            'password' => 'analys_tesis',
        ]);
        $user->assignRole('Analista-Carrera');
    }
}
