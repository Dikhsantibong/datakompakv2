<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Pagination\Paginator;
use App\Models\WoBacklog;
use App\Observers\WoBacklogObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\StatementPrepared;

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

        // Handle database connections
        DB::beforeExecuting(function ($query) {
            if (stripos($query, 'select') === 0) {
                DB::disconnect();
            }
        });

        // Listen for statement preparation
        DB::listen(function ($query) {
            if ($query->connectionName === 'mysql_poasia') {
                // Force close prepared statements after each query
                DB::connection('mysql_poasia')->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
                DB::connection('mysql_poasia')->getPdo()->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            }
        });

        if (!app()->runningInConsole()) {
            register_shutdown_function(function () {
                DB::disconnect();
            });
        }

        // Set global session variables
        try {
            DB::statement("SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'");
            
            // Try to set global variable if user has privileges
            DB::statement("SET GLOBAL max_prepared_stmt_count=1000000");
        } catch (\Exception $e) {
            // Log error but don't stop the application
            \Log::warning("Could not set MySQL variables: " . $e->getMessage());
        }
    }
}