<?php

namespace Modules\User\Providers;

use App\Models\Academic\Student\Student;
use Illuminate\Support\ServiceProvider;
use Modules\User\Contracts\StudentServiceInterface;
use Modules\User\Contracts\UserServiceInterface;
use Modules\User\Services\StudentService;
use Modules\User\Services\UserService;

class UserServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->singleton(
            UserServiceInterface::class,
            UserService::class
        );

        $this->app->bind(
            StudentServiceInterface::class,
            StudentService::class
        );
	}

	public function boot(): void
	{
	}
}
