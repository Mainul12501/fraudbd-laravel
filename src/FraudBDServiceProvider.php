<?php

namespace Mainul12501\Laravel;

use Illuminate\Support\ServiceProvider;

class FraudBDServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package config with application config
        $this->mergeConfigFrom(
            __DIR__ . '/config/fraudbd.php',
            'fraudbd'
        );

        // Register the FraudBD service as a singleton
        $this->app->singleton('fraudbd', function ($app) {
            return new FraudBD();
        });

        // Alias for easier access
        $this->app->alias('fraudbd', FraudBD::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/fraudbd.php' => config_path('fraudbd.php'),
            ], 'fraudbd-config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['fraudbd', FraudBD::class];
    }
}
