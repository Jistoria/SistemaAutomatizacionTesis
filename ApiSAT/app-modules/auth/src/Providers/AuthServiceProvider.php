<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Contracts\AuthServiceInterface;
use Modules\Auth\Services\AuthService;

class AuthServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(
			AuthServiceInterface::class,
			AuthService::class
		);
	}
	
	public function boot(): void
	{
	}
}
