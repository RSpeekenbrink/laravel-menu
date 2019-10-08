<?php

namespace RSpeekenbrink\LaravelMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RSpeekenbrink\LaravelMenu\Menu
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
