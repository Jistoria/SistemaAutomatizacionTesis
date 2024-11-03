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
            'url' => '/panel/admin_tesis_panel/adminTesisPanelScreen',
            'icon' => '<Bars3Icon class="size-7" />',
            'orden' => 1,
            ],
            [
            'name' => 'Lista de estudiante',
            'url' => '/panel/admin_tesis_panel/list/listStudent',
            'icon' => '<Bars3Icon class="size-7" />',
            'orden' => 2,
            ],
        ];

        $this->info('Creando menus...');
        foreach ($menusAdminTesis as $menu) {
            $model = \App\Models\General\Menu::create([
                'name' => $menu['name'],
                'url' => $menu['url'],
                'icon' => $menu['icon'],
                'order' => $menu['orden'],
            ]);

            $model->roles()->attach($idAdminTesis);


        }



        $this->info('Menus creados correctamente');
    }
}
