<?php

namespace RSpeekenbrink\LaravelInertiaMenu\Tests;

use Mockery as m;
use RSpeekenbrink\LaravelInertiaMenu\Contracts\MenuItem;
use RSpeekenbrink\LaravelInertiaMenu\MenuItemCollection;

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
    public function it_can_add_items()
    {
        $this->collection->add(m::mock(MenuItem::class));

        $this->assertCount(1, $this->collection);
    }
}
