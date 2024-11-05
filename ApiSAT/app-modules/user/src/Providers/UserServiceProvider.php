<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Contracts\UserServiceInterface;
use Modules\User\Services\UserService;

class UserServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(
            UserServiceInterface::class,
            UserService::class
        );
	}

	public function boot(): void
	{
	}
}
