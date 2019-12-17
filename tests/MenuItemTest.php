<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use Mockery as m;
use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;

class MenuItemTest extends TestCase
{
    public function testMenuItemCanBeConstructed()
    {
        $item = new MenuItem('test', '/', $this->menu, []);

        $this->assertInstanceOf(MenuItem::class, $item);
    }

    public function testMenuItemsCanBeInstantiatedWithName()
    {
        $name = 'testItem';
        $route = 'test';
        $menu = $this->menu;

        $item = new MenuItem($name, $route, $menu, []);

        $this->assertMenuItemEquals($item, $name, $route, $menu, []);
    }

    public function testMenuItemsCanBeInstantiatedWithAttributes()
    {
        $name = 'testItem';
        $title = 'testTitle';
        $route = 'test';
        $menu = $this->menu;

        $attributes = [
            'title' => $title,
        ];

        $item = new MenuItem($name, $route, $menu, $attributes);

        $this->assertMenuItemEquals($item, $name, $route, $menu, $attributes);
    }

    public function testChildItemCanBeAddedToMenuItem()
    {
        $item = new MenuItem('parent', 'parent', $this->menu, []);

        $this->assertCount(0, $item->getChildren());

        $item->addChild(new MenuItem('child', 'child', $this->menu, []));

        $this->assertCount(1, $item->getChildren());
    }

    public function testItemCanSendMassChildrenAssignmentToAssociatedMenu()
    {
        $closure = function () {
        };

        $menu = m::mock(Menu::class);

        $item = new MenuItem('test', 'test', $menu, []);

        $menu->shouldReceive('loadChildren')->with($item, $closure);

        $item->addChildren($closure);
    }

    public function testItemCanBeConvertedToArray()
    {
        $name = 'testitem';
        $route = 'testroute';
        $menu = $this->menu;
        $attributes = [
            'title' => 'test',
        ];

        $item = new MenuItem($name, $route, $menu, $attributes);

        $expectedResult = array_merge($attributes, ['name' => $name, 'route' => $route, 'active' => false]);

        $this->assertEquals($expectedResult, $item->toArray());
    }

    public function testItemWithChildrenCanBeConvertedToArray()
    {
        $name = 'testitem';
        $route = 'testroute';
        $menu = $this->menu;
        $attributes = [
            'title' => 'test',
        ];

        $item = new MenuItem($name, $route, $menu, $attributes);

        $childName = 'testitem.child';
        $childRoute = 'testroute.child';
        $childAttributes = [
            'title' => 'child',
        ];

        $item->addChild(new MenuItem($childName, $childRoute, $menu, $childAttributes));

        $expectedResult = array_merge($attributes, [
            'name' => $name,
            'route' => $route,
            'active' => false,
            'children' => [
                array_merge($childAttributes, ['name' => $childName, 'route' => $childRoute, 'active' => false]),
            ],
        ]);

        $this->assertEquals($expectedResult, $item->toArray());
    }
}
