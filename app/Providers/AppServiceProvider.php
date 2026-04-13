<?php

namespace App\Providers;

use App\Repositories\ConveyanceRepositoryInterface;
use App\Repositories\EloquentConveyanceRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for( 'login', function ( Request $request ) {
            $email = (string) $request->input( 'email' );

            return Limit::perMinute( 5 )->by( $email . $request->ip() );
        } );

        RateLimiter::for( 'conveyance-write', function ( Request $request ) {
            $key = $request->user()?->id ? (string) $request->user()->id : $request->ip();

            return Limit::perMinute( 30 )->by( $key );
        } );
    }
}
