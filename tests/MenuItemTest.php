<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use Mockery as m;
use RSpeekenbrink\LaravelMenu\Menu;
use RSpeekenbrink\LaravelMenu\MenuItem;
use RSpeekenbrink\LaravelMenu\Exceptions\MissingAssociatedMenuException;

class MenuItemTest extends TestCase
{
    public function testMenuItemCanBeConstructed()
    {
        $item = new MenuItem('test', []);

        $this->assertInstanceOf(MenuItem::class, $item);
    }

    public function testMenuItemsCanBeInstantiatedWithName()
    {
        $name = 'testItem';

        $item = new MenuItem($name, []);

        $this->assertMenuItemEquals($item, $name, []);
    }

    public function testMenuItemsCanBeInstantiatedWithAttributes()
    {
        $name = 'testItem';
        $title = 'testTitle';
        $link = 'testLink';

        $attributes = [
            'title' => $title,
            'link' => $link,
        ];

        $item = new MenuItem($name, $attributes);

        $this->assertMenuItemEquals($item, $name, $attributes);
    }

    public function testChildItemCanBeAddedToMenuItem()
    {
        $item = new MenuItem('parent', []);

        $this->assertCount(0, $item->getChildren());

        $item->addChild(new MenuItem('child', []));

        $this->assertCount(1, $item->getChildren());
    }

    public function testItemWithNoMenuAssociationReturnsExceptionOnMassChildAssignment()
    {
        $item = new MenuItem('test', []);

        $this->expectException(MissingAssociatedMenuException::class);

        $item->addChildren(function () {
        });
    }

    public function testItemCanSendMassChildrenAssignmentToAssociatedMenu()
    {
        $closure = function () {
        };

        $item = new MenuItem('test', []);

        $menu = m::mock(Menu::class)
            ->shouldReceive('loadChildren')
            ->with($item, $closure)
            ->getMock();

        $item->setMenu($menu)->addChildren($closure);
    }

    public function testItemCanBeConvertedToArray()
    {
        $name = 'testitem';
        $attributes = [
            'title' => 'test',
        ];

        $item = new MenuItem($name, $attributes);

        $expectedResult = array_merge($attributes, ['name' => $name]);

        $this->assertEquals($expectedResult, $item->toArray());
    }

    public function testItemWithChildrenCanBeConvertedToArray()
    {
        $name = 'testitem';
        $attributes = [
            'title' => 'test',
        ];

        $item = new MenuItem($name, $attributes);

        $childName = 'testitem.child';
        $childAttributes = [
            'title' => 'child',
        ];

        $item->addChild(new MenuItem($childName, $childAttributes));

        $expectedResult = array_merge($attributes, [
            'name' => $name,
            'children' => [
                array_merge($childAttributes, ['name' => $childName]),
            ],
        ]);

        $this->assertEquals($expectedResult, $item->toArray());
    }
}
