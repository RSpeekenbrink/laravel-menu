<?php

namespace RSpeekenbrink\LaravelInertiaMenu\Tests;

use RSpeekenbrink\LaravelInertiaMenu\Menu;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RSpeekenbrink\LaravelInertiaMenu\MenuItemGroup;
use RSpeekenbrink\LaravelInertiaMenu\Contracts\MenuItem;

class TestCase extends BaseTestCase
{
    /** @var Menu */
    protected $menu;

    public function setUp(): void
    {
        parent::setUp();

        $this->menu = new Menu();
    }

    protected function assertMenuCount(int $expectedCount)
    {
        $this->assertCount($expectedCount, $this->menu->getMenuItems());
    }

    protected function assertMenuItemEquals(MenuItem $menuItem, string $title, string $route)
    {
        $this->assertEquals($title, $menuItem->getTitle());
        $this->assertEquals($route, $menuItem->getRoute());
    }

    protected function assertMenuItemGroupEquals(MenuItemGroup $menuItemGroup, string $namespace, string $title)
    {
        $this->assertEquals($namespace, $menuItemGroup->getNamespace());
        $this->assertEquals($title, $menuItemGroup->getTitle());
    }
}
