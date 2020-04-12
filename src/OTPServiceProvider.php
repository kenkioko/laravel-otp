<?php

namespace Kenkioko\OTP;

use Illuminate\Support\ServiceProvider;
use Kenkioko\OTP\Models\OTP;

class OTPServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('OTP', function ($app) {
          return new OTP;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/translations', 'laravel-otp');

        $this->publishMigrations();
        $this->publishes([__DIR__ . '/translations' => resource_path('lang/vendor/laravel-otp')], 'laravel-otp-translation');
    }

    protected function publishMigrations()
    {
        $this->publishes([
             __DIR__ . "/database/migrations/2019_05_11_000000_create_otps_table.php" => database_path('migrations/' . date("Y_m_d_His", time()) . '_create_otps_table.php'),
        ], 'laravel-otp-migration');
    }
}
