<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use App\Models\General\CategoryArea;
use Illuminate\Console\Command;

class SetCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-categories';

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
        $categories = [
            ['name' => 'Infraestructura en Redes y Servidores', 'description' => 'Descripción de Infraestructura en Redes y Servidores'],
            ['name' => 'Desarrollo de Software', 'description' => 'Descripción de Desarrollo de Software'],
            ['name' => 'Seguridad Informática', 'description' => 'Descripción de Seguridad Informática'],
            ['name' => 'Inteligencia Artificial', 'description' => 'Descripción de Inteligencia Artificial'],
            ['name' => 'Big Data', 'description' => 'Descripción de Big Data'],
            ['name' => 'Blockchain', 'description' => 'Descripción de Blockchain'],
            ['name' => 'Desarrollo de Videojuegos', 'description' => 'Descripción de Desarrollo de Videojuegos'],
            ['name' => 'Desarrollo de Aplicaciones Móviles', 'description' => 'Descripción de Desarrollo de Aplicaciones Móviles'],
            ['name' => 'Desarrollo Web', 'description' => 'Descripción de Desarrollo Web'],
            ['name' => 'Realidad Virtual y Aumentada', 'description' => 'Descripción de Realidad Virtual y Aumentada'],
            ['name' => 'Robótica', 'description' => 'Descripción de Robótica'],
            ['name' => 'Sistemas Embebidos', 'description' => 'Descripción de Sistemas Embebidos'],
            ['name' => 'IoT', 'description' => 'Descripción de IoT'],
            ['name' => 'Ciberseguridad', 'description' => 'Descripción de Ciberseguridad'],
            ['name' => 'Computación Cuántica', 'description' => 'Descripción de Computación Cuántica'],
            ['name' => 'Base de Datos', 'description' => 'Descripción de Base de Datos'],
        ];

        foreach ($categories as $category) {
            $category = CategoryArea::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'created_by_user' => $adminId->id,
                    'updated_by_user' => $adminId->id,
                ]
            );
        }
    }
}
