<?php

namespace Modules\Thesis\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Thesis\Contracts\ThesisTitleServiceInterface;
use Modules\Thesis\Services\ThesisTitleService;

class ThesisServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(
            ThesisTitleServiceInterface::class,
            ThesisTitleService::class
        );
	}

	public function boot(): void
	{
	}
}
