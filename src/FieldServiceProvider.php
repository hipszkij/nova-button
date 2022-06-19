<?php

namespace hipszkij\NovaButton;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-button', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-button', __DIR__.'/../dist/css/field.css');
        });

        $this->routes();
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authenticate::class])
            ->prefix('nova-vendor/hipszkij/nova-button')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/' => config_path(),
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/nova-button.php', 'nova-button');
    }
}
