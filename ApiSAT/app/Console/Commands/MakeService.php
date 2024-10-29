<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--module=}';

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
        $name = $this->argument('name');
        $module = $this->option('module');
        $this->createService($name, $module);
    }

    protected function createService($name, $module = null)
{
    $className = ucwords($name);
    $classModule = str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));

    if ($module) {
        $stub = File::get(app_path('Console/Commands/stubs/service_module.stub'));
        $stub = str_replace(['{{class}}', '{{module}}'], [$className, $classModule], $stub);
        $path = 'app-modules/' . $module . '/src/Services';

        // Asegura que la carpeta exista
        File::ensureDirectoryExists($path);

        $filePath = $path . '/' . $className . '.php';
    } else {
        $stub = File::get(app_path('Console/Commands/stubs/service.stub'));
        $stub = str_replace('{{class}}', $className, $stub);
        $path = app_path('Services');

        // Asegura que la carpeta exista
        File::ensureDirectoryExists($path);

        $filePath = $path . '/' . $className . '.php';
    }

    // Crea el archivo con el contenido
    File::put($filePath, $stub);

    $this->info('Service created successfully.');
}
}
