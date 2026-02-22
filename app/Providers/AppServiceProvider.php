<?php

namespace App\Providers;

use App\Repositories\ConveyanceRepositoryInterface;
use App\Repositories\EloquentConveyanceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind( ConveyanceRepositoryInterface::class, EloquentConveyanceRepository::class );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
