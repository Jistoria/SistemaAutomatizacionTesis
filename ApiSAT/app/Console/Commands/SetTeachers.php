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
            ['first_name' => 'William', 'second_name' => 'Jesus', 'last_name' => 'Zamora', 'second_last_name' => 'Mero'],
            ['first_name' => 'Jorge', 'second_name' => 'Ivan', 'last_name' => 'Pincay', 'second_last_name' => 'Ponce'],
            ['first_name' => 'Edison', 'second_name' => 'Ernesto', 'last_name' => 'Almeida', 'second_last_name' => 'Zambrano'],
            ['first_name' => 'Marco', 'second_name' => 'Wellington', 'last_name' => 'Ayovi', 'second_last_name' => 'Ramirez'],
            ['first_name' => 'Oscar', 'second_name' => 'Armando', 'last_name' => 'Gonzalez', 'second_last_name' => 'Lopez'],
            ['first_name' => 'Robert', 'second_name' => 'Wilfrido', 'last_name' => 'Moreira', 'second_last_name' => 'Centeno'],
            ['first_name' => 'Jose', 'second_name' => 'Cristobal', 'last_name' => 'Arteaga', 'second_last_name' => 'Vera'],
            ['first_name' => 'Luis', 'second_name' => 'Jacinto', 'last_name' => 'Mendoza', 'second_last_name' => 'Cuzme'],
            ['first_name' => 'John', 'second_name' => 'Antonio', 'last_name' => 'Cevallos', 'second_last_name' => 'Macias'],
            ['first_name' => 'Joffre', 'second_name' => 'Edgardo', 'last_name' => 'Panchana', 'second_last_name' => 'Flores'],
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
