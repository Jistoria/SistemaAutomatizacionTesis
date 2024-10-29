<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitServiceApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:service-api';

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
        // Crear el archivo .env si no existe
        // if (!file_exists(base_path('.env'))) {
        //     copy(base_path('.env.example'), base_path('.env'));
        //     $this->info('Archivo .env creado correctamente');
        // }

        // Ejecutar las migraciones
        $this->call('migrate:fresh');

        // Ejecutar el comandos para los roles y permisos
        $this->call('app:set-roles');
        $this->call('app:set-admin-tesis');
        $this->call('app:set-teachers');

        //Generar clave Passport
        $this->call('passport:client', ['--personal' => true]);


        // Mostrar mensaje de finalización
        $this->info('Servicio API inicializado correctamente');
    }
}