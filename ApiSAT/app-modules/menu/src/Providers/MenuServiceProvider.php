<?php

namespace Modules\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Menu\Contracts\MenuServiceInterface;
use Modules\Menu\Services\MenuAuthService;

class MenuServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(MenuServiceInterface::class, MenuAuthService::class);

	}

	public function boot(): void
	{
	}
}
