<?php

namespace RSpeekenbrink\LaravelMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \RSpeekenbrink\LaravelMenu\MenuItem add(string $name, string $route, array $attributes =  [])
 * @method static \RSpeekenbrink\LaravelMenu\MenuItem addIf($condition, string $name, string $route, array $attributes = [])
 * @method static \RSpeekenbrink\LaravelMenu\MenuItem addIfCan($authorization, string $name, string $route, array $attributes = [])
 * @method static \RSpeekenbrink\LaravelMenu\MenuItem getItemByName($name)
 * @method static int getIndexByName($name)
 * @method static \RSpeekenbrink\LaravelMenu\MenuItemCollection getMenuItems()
 * @method static array toArray()
 * @method static void loadChildren(MenuItem $parent, Closure $items)
 * @method static string toJson($options = 0)
 * @method static mixed jsonSerialize()
 *
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
