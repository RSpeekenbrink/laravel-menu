<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use Illuminate\Contracts\Auth\Access\Gate;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;

class TestCase extends BaseTestCase
{
    /** @var Menu */
    protected $menu;

    public function setUp(): void
    {
        parent::setUp();

        $this->menu = new Menu();

        $this->app->make(Gate::class)->define('computerSaysYes', function () {
            return true;
        });

        $this->app->make(Gate::class)->define('computerSaysNo', function () {
            return false;
        });

        $this->app->make(Gate::class)->define('computerSaysMaybe', function ($user, $argument) {
            return $argument;
        });
    }

    protected function assertMenuCount(int $expectedCount)
    {
        $this->assertCount($expectedCount, $this->menu->getMenuItems());
    }

    protected function assertMenuItemEquals(MenuItem $menuItem, string $name, string $route, Menu $menu, array $attributes)
    {
        $this->assertInstanceOf(MenuItem::class, $menuItem);
        $this->assertEquals($name, $menuItem->getName());
        $this->assertEquals($attributes, $menuItem->getAttributes());
        $this->assertEquals($route, $menuItem->getRoute());
        $this->assertEquals($menu, $menuItem->getMenu());
    }
}
