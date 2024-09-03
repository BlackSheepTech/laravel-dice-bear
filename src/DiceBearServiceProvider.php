<?php

namespace BlackSheepTech\DiceBear;

use Illuminate\Support\ServiceProvider;

/**
 * @internal
 */
class DiceBearServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/dice-bear.php', 'dice-bear'
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {

        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/dice-bear.php' => config_path('dice-bear.php'),
            ], ['dice-bear', 'dice-bear-config']);
        }
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }
}
