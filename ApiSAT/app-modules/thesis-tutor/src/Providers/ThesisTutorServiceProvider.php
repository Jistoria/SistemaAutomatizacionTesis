<?php

namespace Modules\ThesisTutor\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ThesisTutor\Http\Middleware\ensureIsStudentTutor;

class ThesisTutorServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $router = $this->app['router'];
        $router->aliasMiddleware('ensureIsStudentTutor', ensureIsStudentTutor::class);

	}

	public function boot(): void
	{
	}
}
