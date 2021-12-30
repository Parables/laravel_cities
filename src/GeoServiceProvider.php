<?php

namespace Mdhesari\LaravelCities;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace;

    public $customRoutes;

    public function boot()
    {
        $this->handleMigrations();
        $this->handleRoutes();
        $this->handleConsoleCommands();
    }

    /*--------------------------------------------------------------------------
    | Register Console Commands
    |--------------------------------------------------------------------------*/

    private function handleConsoleCommands()
    {
        // Register Console Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Mdhesari\LaravelCities\Commands\SeedGeoFile::class,
                \Mdhesari\LaravelCities\Commands\SeedJsonFile::class,
                \Mdhesari\LaravelCities\Commands\BuildPplTree::class,
                \Mdhesari\LaravelCities\Commands\Download::class,
            ]);
        }
    }

    /*--------------------------------------------------------------------------
    | Register Routes
    |--------------------------------------------------------------------------*/

    private function handleRoutes()
    {
        $route = Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace);

        if ($this->customRoutes) {
            $route->group($this->customRoutes);
        } else {
            $route->group(__DIR__ . '/../routes/api.php');
        }
    }

    /*--------------------------------------------------------------------------
    | Database Migrations
    |--------------------------------------------------------------------------*/

    private function handleMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Optional: Publish the migrations:
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations'),
        ], 'laravel-cities-migrations');
    }

}
