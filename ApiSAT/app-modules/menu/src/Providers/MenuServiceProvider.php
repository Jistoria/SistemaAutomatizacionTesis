<?php

namespace Modules\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Menu\Contracts\MenuServiceInterface;
use Modules\Menu\Services\MenuService;

class MenuServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(MenuServiceInterface::class, MenuService::class);

	}

	public function boot(): void
	{
	}
}
