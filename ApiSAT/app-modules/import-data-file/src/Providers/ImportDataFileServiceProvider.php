<?php

namespace Modules\ImportDataFile\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\PeriodAcademic\Services\PeriodAcademicService;

class ImportDataFileServiceProvider extends ServiceProvider
{
	public function register(): void
	{

	}

	public function boot(): void
	{
	}
}
