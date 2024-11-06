<?php

namespace Modules\PeriodAcademic\Providers;

use App\Models\Academic\PeriodAcademic;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
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

}

	public function boot(): void
	{
	}
}
