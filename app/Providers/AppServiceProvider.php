<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        setlocale(LC_TIME, 'id_ID');
        
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('companies')) {
                $company = \App\Models\Company::first();
                // If no company exists yet, avoid error by not sharing or sharing null
                if ($company) {
                    \Illuminate\Support\Facades\View::share('company', $company);
                }
            }
        } catch (\Exception $e) {
            // Handle migration edge cases silently
        }
    }
}
