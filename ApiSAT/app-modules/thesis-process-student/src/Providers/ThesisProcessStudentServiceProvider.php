<?php

namespace Modules\ThesisProcessStudent\Providers;

use App\Models\Academic\Thesis\Requirement\Requirement;
use Illuminate\Support\ServiceProvider;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;
use Modules\ThesisProcessStudent\Services\RequirementsStudentService;

class ThesisProcessStudentServiceProvider extends ServiceProvider
{
	public function register(): void
	{
        $this->app->bind(
            \Modules\ThesisProcessStudent\Contracts\ThesisProcessStudentServiceInterface::class,
            \Modules\ThesisProcessStudent\Services\ThesisProcessStudentService::class
        );

        $this->app->bind(
            RequirementsStudentServiceInterface::class,
            RequirementsStudentService::class
        );
	}

	public function boot(): void
	{
	}
}
