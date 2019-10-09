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
        $this->collection = new MenuItemCollection();

        parent::setUp();
    }

    /** @test */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf(MenuItemCollection::class, $this->collection);
    }

    /** @test */
    public function it_can_add_menu_items()
    {
        $this->collection->add(m::mock(MenuItem::class));

        $this->assertCount(1, $this->collection);
    }

    /** @test */
    public function it_can_tell_if_it_has_item_with_name()
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

        $this->assertTrue($this->collection->hasName($itemName));
        $this->assertTrue($this->collection->hasName($childItemName));
        $this->assertFalse($this->collection->hasName('NotExisting'));
    }

    /** @test */
    public function it_can_get_nested_item_by_name()
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

        $this->assertEquals($item, $this->collection->getItemByName($itemName));
        $this->assertEquals($child, $this->collection->getItemByName($childItemName));
    }
}
