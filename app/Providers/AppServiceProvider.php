<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            $defaultConnection = config('database.default');

            if ($defaultConnection === 'sqlite') {
                throw new RuntimeException('Production database must not use sqlite. Configure PostgreSQL before boot.');
            }

            if (!in_array($defaultConnection, ['pgsql', 'mysql', 'mariadb', 'sqlsrv'], true)) {
                throw new RuntimeException("Unsupported production database driver [{$defaultConnection}].");
            }

            DB::connection()->getPdo();
        }
    }
}
