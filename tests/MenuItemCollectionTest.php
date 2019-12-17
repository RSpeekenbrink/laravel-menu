<?php

namespace RSpeekenbrink\LaravelMenu\Tests;

use Mockery as m;
use RSpeekenbrink\LaravelMenu\MenuItem;
use RSpeekenbrink\LaravelMenu\MenuItemCollection;

class MenuItemCollectionTest extends TestCase
{
    /** @var MenuItemCollection */
    protected $collection;

    public function setUp(): void
    {
        parent::setUp();

        $this->collection = new MenuItemCollection();
    }

    public function testCollectionCanBeInstantiated()
    {
        $this->assertInstanceOf(MenuItemCollection::class, $this->collection);
    }

    public function testMenuItemsCanBeAddedToTheCollection()
    {
        $this->collection->add(m::mock(MenuItem::class));

        $this->assertCount(1, $this->collection);
    }

    public function testCollectionCanTellIfItHasItemWithName()
    {
        $itemName = 'itemname';
        $item = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $itemName,
            'getChildren' => new MenuItemCollection(),
        ])->getMock();

        $this->collection->add($item);

        $this->assertTrue($this->collection->hasName($itemName));
        $this->assertFalse($this->collection->hasName('NotExisting'));
    }

    public function testCollectionCanTellIfItHasNestedItemWithName()
    {
        $childItemName = 'childname';
        $child = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $childItemName,
            'getChildren' => new MenuItemCollection(),
        ])->getMock();

        $childCollection = new MenuItemCollection([$child]);

        $itemName = 'itemname';
        $item = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $itemName,
            'getChildren' => $childCollection,
        ])->getMock();

        $this->collection->add($item);

        $this->assertTrue($this->collection->hasName($childItemName));
    }

    public function testCollectionCanFindItemByName()
    {
        $itemName = 'itemname';
        $item = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $itemName,
            'getChildren' => new MenuItemCollection(),
        ])->getMock();

        $this->collection->add($item);

        $this->assertEquals($item, $this->collection->getItemByName($itemName));
    }

    public function testCollectionCanFindNestedItemByName()
    {
        $childItemName = 'childname';
        $child = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $childItemName,
            'getChildren' => new MenuItemCollection(),
        ])->getMock();

        $childCollection = new MenuItemCollection([$child]);

        $itemName = 'itemname';
        $item = m::mock(MenuItem::class)->shouldReceive([
            'getName' => $itemName,
            'getChildren' => $childCollection,
        ])->getMock();

        $this->collection->add($item);

        $this->assertEquals($child, $this->collection->getItemByName($childItemName));
    }
}
