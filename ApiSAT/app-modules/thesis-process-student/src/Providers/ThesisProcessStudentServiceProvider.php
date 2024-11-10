<?php

namespace Modules\ThesisProcessStudent\Providers;

use Illuminate\Support\ServiceProvider;

class ThesisProcessStudentServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->bind(
            \Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface::class,
            \Modules\ThesisProcessStudent\Services\ThesisProcessStudentService::class
        );
	}

	public function boot(): void
	{
	}
}
