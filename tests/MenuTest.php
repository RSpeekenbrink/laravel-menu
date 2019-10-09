<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;

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
        $name = 'testItem';
        $title = 'testTitle';
        $link = 'testLink';

        $attributes = [
            'title' => $title,
            'link' => $link,
        ];

        $this->menu->add($name, $attributes);

        $this->assertMenuCount(1);

        $item = $this->menu->getMenuItems()->get(0);

        $this->assertInstanceOf(MenuItem::class, $item);
        $this->assertMenuItemEquals($item, $name, $attributes);
    }
}
