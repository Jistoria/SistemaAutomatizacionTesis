<?php

namespace App\Console\Commands;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Console\Command;

class SetTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-teachers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear docentes y asignarles el rol de Docente-tesis';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Lista de docentes con nombres y apellidos completos
        $teachers = [
            ['first_name' => 'WILLIAM', 'second_name' => 'JESUS', 'last_name' => 'ZAMORA', 'second_last_name' => 'MERO'],
            ['first_name' => 'JORGE', 'second_name' => 'IVAN', 'last_name' => 'PINCAY', 'second_last_name' => 'PONCE'],
            ['first_name' => 'EDISON', 'second_name' => 'ERNESTO', 'last_name' => 'ALMEIDA', 'second_last_name' => 'ZAMBRANO'],
            ['first_name' => 'MARCO', 'second_name' => 'WELLINGTON', 'last_name' => 'AYOVI', 'second_last_name' => 'RAMIREZ'],
            ['first_name' => 'OSCAR', 'second_name' => 'ARMANDO', 'last_name' => 'GONZALEZ', 'second_last_name' => 'LOPEZ'],
            ['first_name' => 'ROBERT', 'second_name' => 'WILFRIDO', 'last_name' => 'MOREIRA', 'second_last_name' => 'CENTENO'],
            ['first_name' => 'JOSE', 'second_name' => 'CRISTOBAL', 'last_name' => 'ARTEAGA', 'second_last_name' => 'VERA'],
            ['first_name' => 'LUIS', 'second_name' => 'JACINTO', 'last_name' => 'MENDOZA', 'second_last_name' => 'CUZME'],
            ['first_name' => 'JOHN', 'second_name' => 'ANTONIO', 'last_name' => 'CEVALLOS', 'second_last_name' => 'MACIAS'],
            ['first_name' => 'JOFFRE', 'second_name' => 'EDGARDO', 'last_name' => 'PANCHANA', 'second_last_name' => 'FLORES'],
        ];

        // Buscar o crear el rol Docente-tesis
        $role = Role::firstOrCreate(['name' => 'Docente-tesis']);

        foreach ($teachers as $teacher) {
            // Generar UUID y email basado en el primer nombre y primer apellido
            $uuid = \Ramsey\Uuid\Uuid::uuid4();
            $email = strtolower($teacher['first_name'] . '.' . $teacher['last_name'] . '@uleam.edu.ec');

            // Concatenar el nombre completo
            $fullName = $teacher['first_name'] . ' ' . $teacher['second_name'] . ' ' . $teacher['last_name'] . ' ' . $teacher['second_last_name'];

            // Crear o buscar el usuario docente
            $user = User::firstOrCreate(
                ['email' => $email], // Buscar por email
                [
                    'id' => $uuid,
                    'name' => $fullName,
                    'email' => $email,
                    'password' => 'process_thesis', // Cambia la contraseña en producción
                ]
            );

            // Asignar el rol de Docente-tesis al usuario
            if (!$user->hasRole('Docente-tesis')) {
                $user->assignRole($role);
            }

            $this->info("Docente {$user->name} creado/actualizado y rol asignado.");
        }

        return 0;
    }
}