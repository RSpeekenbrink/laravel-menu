<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;

class MenuTest extends TestCase
{
    public function testMenuCanBeConstructed()
    {
        $this->assertInstanceOf(Menu::class, $this->menu);
    }

    public function testItemsCanBeAddedToMenu()
    {
        $this->menu->add('test', []);

        $this->assertMenuCount(1);
    }

    public function testItemsAddedToMenuHaveTheCorrectType()
    {
        $this->menu->add('test', []);

        $this->assertInstanceOf(MenuItem::class, $this->menu->getMenuItems()->get(0));
    }

    public function testChildrenCanBeAddedWithClosure()
    {
        $this->menu->add('test', [])->addChildren(function () {
            $this->menu->add('child', []);
        });

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);

        $this->assertCount(1, $item->getChildren());
    }

    public function testChildrenGetTheCorrectNamePrefix()
    {
        $name = 'test';
        $childName = 'child';
        $expectedName = $name.'.'.$childName;

        $this->menu->add($name, [])->addChildren(function () use ($childName) {
            $this->menu->add($childName, []);
        });

        $child = $this->menu->getMenuItems()->get(0)->getChildren()->get(0);

        $this->assertEquals($expectedName, $child->getName());
    }
}
