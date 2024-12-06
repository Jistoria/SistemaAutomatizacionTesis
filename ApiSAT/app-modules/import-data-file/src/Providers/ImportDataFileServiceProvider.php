<?php

namespace Modules\ImportDataFile\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ImportDataFile\Contracts\ImportDataFileServiceInterface;
use Modules\ImportDataFile\Services\ImportDataFileService;

class ImportDataFileServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->bind(
            ImportDataFileServiceInterface::class,
            ImportDataFileService::class
        );
	}

	public function boot(): void
	{
	}
}
