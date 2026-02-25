<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Company;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        \Carbon\Carbon::setLocale('id');

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('companies')) {
                $company = \App\Models\Company::first();
                // Cukup gunakan ini saja untuk semua layout
                \Illuminate\Support\Facades\View::share('company', $company);
            }
        } catch (\Exception $e) {}
    }
}
