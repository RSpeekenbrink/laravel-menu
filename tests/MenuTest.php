<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use Illuminate\Auth\GenericUser;
use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;

class MenuTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        auth()->login(new GenericUser(['id' => 1]));
    }

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

    public function testItemIsAddedWhenUserHasAbility()
    {
        $this->menu->addIfCan('computerSaysYes', 'test', []);

        $this->assertMenuCount(1);
    }

    public function testItemIsNotAddedWhenUserDoesntHaveAbility()
    {
        $this->menu->addIfCan('computerSaysNo', 'test', []);

        $this->assertMenuCount(0);
    }

    public function testArgumentGetsParsedWhenArrayIsPassed()
    {
        $this->menu->addIfCan(['computerSaysMaybe', false], 'test', []);

        $this->assertMenuCount(0);

        $this->menu->addIfCan(['computerSaysMaybe', true], 'test', []);

        $this->assertMenuCount(1);
    }

    public function testItemIsAddedWhenConditionIsTrue()
    {
        $this->menu->addIf(true, 'condition', []);

        $this->assertMenuCount(1);

        $this->menu->addIf(function () {
            return true;
        }, 'closure', []);

        $this->assertMenuCount(2);
    }

    public function testItemIsNotAddedWhenConditionIsFalse()
    {
        $this->menu->addIf(false, 'condition', []);

        $this->assertMenuCount(0);

        $this->menu->addIf(function () {
            return false;
        }, 'closure', []);

        $this->assertMenuCount(0);
    }
}
