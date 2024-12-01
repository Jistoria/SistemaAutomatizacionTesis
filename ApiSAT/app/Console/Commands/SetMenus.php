<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetMenus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-menus';

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
        $this->info('Set Menus');

        $idAdminTesis = \Spatie\Permission\Models\Role::where('name', 'Administrador-tesis')->first()->uuid;

        $menusAdminTesis = [
            [
            'name' => 'Dashboard',
            'url' => '/',
            'icon' => 'bi bi-clipboard2-data-fill',
            'orden' => 0,
            ],
            [
            'name' => 'Estudiantes',
            'url' => '/panel/list/listStudent',
            'icon' => 'bi bi-person-vcard-fill',
            'orden' => 1,
            ],
            [
            'name' => 'Docentes',
            'url' => '/panel/list/listTeacher',
            'icon' => 'bi bi-person-rolodex',
            'orden' => 2,
            ],
            [
            'name' => 'Tribunales',
            'url' => '/panel/list/listCourt',
            'icon' => '',
            'orden' => 3,
            ],
            [
            'name' => 'Sustentaciones',
            'url' => '/panel/list/listDefense',
            'icon' => 'bi bi-diagram-3-fill',
            'orden' => 4,
            ],
            [
            'name' => 'Personal',
            'url' => '/panel/list/listManagment',
            'icon' => 'bi bi-person-workspace',
            'orden' => 5,
            ],
        ];

        $this->info('Creando menu Administrador...');
        foreach ($menusAdminTesis as $menu) {
            $model = \App\Models\General\Menu::create([
                'name' => $menu['name'],
                'url' => $menu['url'],
                'icon' => $menu['icon'],
                'order' => $menu['orden'],
            ]);

            $model->roles()->attach($idAdminTesis);


        }

        $uuidRoleTeacher = \Spatie\Permission\Models\Role::where('name', 'Docente-tesis')->first()->uuid;

        $menusTeacher = [
            [
            'name' => 'Dashboard',
            'url' => '/',
            'icon' => 'bi bi-clipboard2-data-fill',
            'orden' => 0,
            ],
            [
            'name' => 'Estudiantes',
            'url' => '/panel/list/listStudent',
            'icon' => 'bi bi-person-vcard-fill',
            'orden' => 1,
            ],
            [
            'name' => 'Tribunales',
            'url' => '/panel/list/listCourt',
            'icon' => 'bi bi-calendar-week-fill',
            'orden' => 2,
            ],
        ];


        $this->info('Creando menu Docente...');

        foreach ($menusTeacher as $menu) {
            $model = \App\Models\General\Menu::create([
                'name' => $menu['name'],
                'url' => $menu['url'],
                'icon' => $menu['icon'],
                'order' => $menu['orden'],
            ]);

            $model->roles()->attach($uuidRoleTeacher);
        }

        $uuidRoleAnalyst = \Spatie\Permission\Models\Role::where('name', 'Analista-Carrera')->first()->uuid;

        $menusAnalyst = [
            [
            'name' => 'Dashboard',
            'url' => '/',
            'icon' => 'bi bi-clipboard2-data-fill',
            'orden' => 0,
            ],
            [
            'name' => 'Estudiantes',
            'url' => '/panel/list/listStudent',
            'icon' => 'bi bi-person-vcard-fill',
            'orden' => 1,
            ],
            [
            'name' => 'Tribunales',
            'url' => '/panel/list/listCourt',
            'icon' => 'bi bi-calendar-week-fill',
            'orden' => 2,
            ],
        ];

        $this->info('Creando menu Analista...');
        foreach ($menusAnalyst as $menu) {
            $model = \App\Models\General\Menu::create([
                'name' => $menu['name'],
                'url' => $menu['url'],
                'icon' => $menu['icon'],
                'order' => $menu['orden'],
            ]);

            $model->roles()->attach($uuidRoleAnalyst);
        }

        $this->info('Menus creados correctamente');
    }
}
