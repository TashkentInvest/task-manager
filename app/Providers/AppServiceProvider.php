<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use App\Models\Tasks;
use App\Observers\TasksHistoryObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Tasks::observe(TasksHistoryObserver::class);
        
        // Laravel Pagination use Bootstrap
        Paginator::useBootstrap();

        // Connect Helpers
        foreach (glob(__DIR__.'/../Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
