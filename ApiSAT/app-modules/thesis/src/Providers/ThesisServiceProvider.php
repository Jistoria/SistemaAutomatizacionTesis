<?php

namespace Modules\Thesis\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Thesis\Contracts\ThesisPhasesServiceInterface;
use Modules\Thesis\Contracts\ThesisProcessServiceInterface;
use Modules\Thesis\Contracts\ThesisTitleServiceInterface;
use Modules\Thesis\Services\ThesisPhasesService;
use Modules\Thesis\Services\ThesisProcessService;
use Modules\Thesis\Services\ThesisTitleService;

class ThesisServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->bind(
            ThesisTitleServiceInterface::class,
            ThesisTitleService::class
        );

        $this->app->bind(
            ThesisProcessServiceInterface::class,
            ThesisProcessService::class
        );

        $this->app->bind(
           ThesisPhasesServiceInterface::class,
            ThesisPhasesService::class
        );
	}

	public function boot(): void
	{
	}
}
