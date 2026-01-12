<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\{
    StateRepository,
    UserRepository,
    CountryRepository
};
use App\Repositories\Interfaces\{
    StateRepositoryInterface,
    UserRepositoryInterface,
    CountryRepositoryInterface
};

class RepositoryServiceProvider extends ServiceProvider
{
        
    /**
     * Method register
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Method boot
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
