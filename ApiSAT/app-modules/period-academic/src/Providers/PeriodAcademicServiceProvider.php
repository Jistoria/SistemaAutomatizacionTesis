<?php

namespace Modules\PeriodAcademic\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ImportDataFile\Events\ProcessThesisDataGenerated;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\PeriodAcademic\Listeners\SavePeriodAcademicListener;
use Modules\PeriodAcademic\Services\PeriodAcademicService;

class PeriodAcademicServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProcessThesisDataGenerated::class => [
            SavePeriodAcademicListener::class,
        ],
    ];
	public function register(): void
	{
        $this->app->singleton(PeriodAcademicServiceInterface::class, PeriodAcademicService::class);
	}

	public function boot(): void
	{
	}
}
