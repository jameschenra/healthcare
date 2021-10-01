<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\WorkingHour;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $contactInfo = DB::table('contact_infos')->first();
        $workingHours = WorkingHour::get();

        view()->share('contactInfo', $contactInfo);
        view()->share('workingHours', $workingHours);
    }
}
