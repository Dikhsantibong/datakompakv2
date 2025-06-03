<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Pagination\Paginator;
use App\Models\WoBacklog;
use App\Observers\WoBacklogObserver;
use Illuminate\Support\Facades\DB;

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
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('user', UserMiddleware::class);
        
        Paginator::useBootstrap();
        WoBacklog::observe(WoBacklogObserver::class);

        // Improved database connection handling
        DB::beforeExecuting(function ($query) {
            // Close any existing connections before heavy queries
            if (stripos($query, 'select') === 0) {
                DB::disconnect();
            }
        });

        // Clean up connections periodically
        if (!app()->runningInConsole()) {
            register_shutdown_function(function () {
                DB::disconnect();
            });
        }

        // Set global session variables
        DB::statement("SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'");
        DB::statement("SET SESSION max_prepared_stmt_count=0");
    }
}