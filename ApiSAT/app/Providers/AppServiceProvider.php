<?php

namespace App\Providers;

use App\Models\Academic\PeriodAcademic;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\PeriodAcademic\Contracts\PeriodAcademicServiceInterface;
use Modules\PeriodAcademic\Services\PeriodAcademicService;
use Illuminate\Routing\UrlGenerator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {}

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }

        Passport::tokensExpireIn(Carbon::now()->addMinutes(10));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));


    }
}
