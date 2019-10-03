<?php

namespace RSpeekenbrink\LaravelInertiaMenu\Tests;

use RSpeekenbrink\LaravelInertiaMenu\Menu;
use RSpeekenbrink\LaravelInertiaMenu\MenuItem;
use RSpeekenbrink\LaravelInertiaMenu\MenuItemGroup;

class MenuTest extends TestCase
{
    /** @test */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf(Menu::class, $this->menu);
    }

    /** @test */
    public function it_can_add_menu_items()
    {
        $title = 'TestItem';
        $route = 'test.route';
        $this->menu->add($title, $route);

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);

        $this->assertInstanceOf(MenuItem::class, $item);
        $this->assertMenuItemEquals($item, $title, $route);
    }

    /** @test */
    public function it_can_add_menu_items_on_true_condition()
    {
        $title = 'TestItem';
        $route = 'test.route';
        $this->menu->addIf($title, $route, true);

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);

        $this->assertInstanceOf(MenuItem::class, $item);
        $this->assertMenuItemEquals($item, $title, $route);
    }

    /** @test */
    public function it_does_not_add_menu_items_on_false_condition()
    {
        $title = 'TestItem';
        $route = 'test.route';
        $this->menu->addIf($title, $route, false);

        $this->assertMenuCount(0);
    }

    /** @test */
    public function it_can_add_menu_item_groups()
    {
        $groupTitle = 'TestGroup';
        $groupNamespace = 'testgroup';

        $this->menu->group($groupNamespace, $groupTitle, function () {
        });

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);

        $this->assertInstanceOf(MenuItemGroup::class, $item);
        $this->assertMenuItemGroupEquals($item, $groupNamespace, $groupTitle);
    }

    /** @test */
    public function it_can_have_items_in_item_group()
    {
        $groupTitle = 'TestGroup';
        $groupNamespace = 'testgroup';
        $title = 'ItemTitle';
        $route = 'testgroup.someitem.route';

        $this->menu->group($groupNamespace, $groupTitle, function () use ($title, $route) {
            $this->menu->add($title, $route);
        });

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);
        $this->assertInstanceOf(MenuItemGroup::class, $item);

        $children = $item->getChildren();
        $this->assertCount(1, $children);

        $child = $children->get(0);
        $this->assertInstanceOf(MenuItem::class, $child);
        $this->assertMenuItemEquals($child, $title, $route);
    }

    /** @test */
    public function it_can_add_items_to_group_after_creation()
    {
        $groupTitle = 'TestGroup';
        $groupNamespace = 'testgroup';
        $title = 'ItemTitle';
        $route = 'testgroup.someitem.route';
        $secondTitle = 'AnotherItem';
        $secondRoute = 'secondroute';

        // Initialize Group
        $this->menu->group($groupNamespace, $groupTitle, function () use ($title, $route) {
            $this->menu->add($title, $route);
        });

        // Call to same group
        $this->menu->group($groupNamespace, $groupTitle, function () use ($secondTitle, $secondRoute) {
            $this->menu->add($secondTitle, $secondRoute);
        });

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);
        $this->assertInstanceOf(MenuItemGroup::class, $item);

        $children = $item->getChildren();
        $this->assertCount(2, $children);

        $child = $children->get(0);
        $this->assertInstanceOf(MenuItem::class, $child);
        $this->assertMenuItemEquals($child, $title, $route);

        $child = $children->get(1);
        $this->assertInstanceOf(MenuItem::class, $child);
        $this->assertMenuItemEquals($child, $secondTitle, $secondRoute);
    }

    /** @test */
    public function it_can_nest_groups()
    {
        $groupTitle = 'TestGroup';
        $groupNamespace = 'testgroup';
        $secondGroupTitle = 'SecondGroup';
        $secondGroupNamespace = 'secondgroup';
        $title = 'Item';
        $route = 'Route';

        // Initialize Group
        $this->menu->group($groupNamespace, $groupTitle, function () use (
            $secondGroupNamespace,
            $secondGroupTitle,
            $title,
            $route
        ) {
            $this->menu->group($secondGroupNamespace, $secondGroupTitle, function () use ($title, $route) {
                $this->menu->add($title, $route);
            });
        });

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);
        $this->assertInstanceOf(MenuItemGroup::class, $item);

        $children = $item->getChildren();
        $this->assertCount(1, $children);

        $child = $children->get(0);
        $this->assertInstanceOf(MenuItemGroup::class, $child);
        $this->assertMenuItemGroupEquals($child, $secondGroupNamespace, $secondGroupTitle);
        $this->assertCount(1, $child->getChildren());

        $child = $child->getChildren()->get(0);
        $this->assertInstanceOf(MenuItem::class, $child);
        $this->assertMenuItemEquals($child, $title, $route);
    }

    /** @test */
    public function it_can_return_menu_as_array()
    {
        $groupTitle = 'TestGroup';
        $groupNamespace = 'testgroup';
        $title = 'ItemTitle';
        $route = 'testgroup.someitem.route';

        $this->menu->group($groupNamespace, $groupTitle, function () use ($title, $route) {
            $this->menu->add($title, $route);
        });

        $array = $this->menu->toArray();

        $this->assertIsArray($array); //TODO: Validate Array instead
    }
}
