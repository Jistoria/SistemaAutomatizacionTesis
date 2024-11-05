<?php

namespace Modules\Degree\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Degree\Contracts\DegreeServiceInterface;
use Modules\Degree\Services\DegreeService;

class DegreeServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(
            DegreeServiceInterface::class,
            DegreeService::class
        );
	}

	public function boot(): void
	{
	}
}
