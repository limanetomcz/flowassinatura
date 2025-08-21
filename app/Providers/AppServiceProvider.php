<?php

namespace App\Providers;

use App\Http\Requests\CompanyFormRequest;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar FormRequests como singletons para uso em Livewire
        $this->app->singleton(CompanyFormRequest::class, function ($app) {
            return new CompanyFormRequest();
        });

        $this->app->singleton(UserFormRequest::class, function ($app) {
            return new UserFormRequest();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
