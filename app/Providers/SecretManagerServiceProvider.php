<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AwsSecretManagerService;

class SecretManagerServiceProvider extends ServiceProvider
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
        //Only run secret manager if enabled from environment
        if (config('secret-manager.enableSecretManager')) {
            $secretsManager = new AwsSecretManagerService();
            $secretsManager->loadSecrets();
        }
    }
}
