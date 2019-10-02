<?php

namespace RSpeekenbrink\LaravelInertiaMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RSpeekenbrink\LaravelInertiaMenu\Menu
 */
class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu';
    }
}
