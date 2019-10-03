<?php

namespace RSpeekenbrink\LaravelInertiaMenu;

use Inertia\Inertia;
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
        $this->registerInertiaShare();
    }

    protected function registerMenu()
    {
        $this->app->singleton('menu', function ($app) {
            return new Menu();
        });
    }

    protected function registerInertiaShare()
    {
        $menu = $this->app['menu'];

        Inertia::share([
            'menu' => function () use ($menu) {
                return $menu->toArray();
            },
        ]);
    }
}
