<?php

namespace myPusher\Pusher;

use Illuminate\Support\ServiceProvider;

class PusherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('myPusher\Pusher\PusherController'); // <- when create new controller
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }
}
