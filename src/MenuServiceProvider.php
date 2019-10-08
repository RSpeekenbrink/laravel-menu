<?php

namespace RSpeekenbrink\LaravelMenu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMenu();
    }

    protected function registerMenu()
    {
        $this->app->singleton('menu', function ($app) {
            return new Menu();
        });
    }
}
