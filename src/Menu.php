<?php

namespace RSpeekenbrink\LaravelInertiaMenu;

use Illuminate\Contracts\Support\Arrayable;

class Menu implements Arrayable
{
    /** @var MenuItemCollection */
    protected $menuItems;

    /** @var array */
    protected $groupStack = [];

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->menuItems = new MenuItemCollection();
    }

    /**
     * Add a new menuItem to the menu.
     *
     * @param $title
     * @param $route
     * @return Contracts\MenuItem
     */
    public function add($title, $route)
    {
        return $this->createItem($title, $route);
    }

    /**
     * Add a new menuItemGroup to the menu.
     *
     * @param string $namespace
     * @param string $title
     * @param \Closure $items
     */
    public function group($namespace, $title, \Closure $items)
    {
        $this->updateGroupStack($namespace, $title);

        $this->loadItems($items);

        array_pop($this->groupStack);
    }

    /**
     * Update the groupStack with a new/existing group.
     *
     * @param string $namespace
     * @param string $title
     */
    protected function updateGroupStack($namespace, $title)
    {
        $group = $this->getGroup($namespace, $title);

        $this->groupStack[] = $group;
    }

    /**
     * Get or create group by namespace.
     *
     * @param string $namespace
     * @param string $title
     * @return MenuItemGroup
     */
    public function getGroup($namespace, $title)
    {
        if ($group = $this->getGroupByNamespace($this->menuItems, $namespace)) {
            return $group;
        }

        return $this->createGroup($namespace, $title);
    }

    /**
     * Get group by namespace in given MenuItemCollection.
     *
     * @param MenuItemCollection $itemCollection
     * @param string $namespace
     * @return MenuItemGroup
     */
    protected function getGroupByNamespace($itemCollection, $namespace)
    {
        return $itemCollection->getGroupByNamespace($namespace);
    }

    /**
     * @param \Closure $items
     */
    protected function loadItems(\Closure $items)
    {
        $items($this);
    }

    /**
     * Create new group instance.
     *
     * @param string $namespace
     * @param string $title
     * @return MenuItemGroup
     */
    protected function createGroup($namespace, $title)
    {
        $group = $this->newGroup($namespace)->setTitle($title);

        if ($this->hasGroupStack()) {
            return $this->addItemToLastGroup($group);
        }

        $this->menuItems->add($group);

        return $group;
    }

    /**
     * Create new group object.
     *
     * @param $namespace
     * @return MenuItemGroup
     */
    protected function newGroup($namespace)
    {
        return new MenuItemGroup($namespace);
    }

    /**
     * Create new MenuItem instance.
     *
     * @param string $title
     * @param string $route
     * @return Contracts\MenuItem
     */
    protected function createItem($title, $route)
    {
        $item = $this->newItem($title, $route);

        if ($this->hasGroupStack()) {
            return $this->addItemToLastGroup($item);
        }

        return $this->menuItems->add($item);
    }

    /**
     * Add item to last group in the groupStack.
     *
     * @param MenuItem $item
     * @return Contracts\MenuItem
     */
    protected function addItemToLastGroup(MenuItem $item)
    {
        if ($group = end($this->groupStack)) {
            return $group->addChild($item);
        }
    }

    /**
     * Create new MenuItem object.
     *
     * @param $title
     * @param $route
     * @return MenuItem
     */
    protected function newItem($title, $route)
    {
        return new MenuItem($title, $route);
    }

    /**
     * Returns if Menu has GroupStack.
     *
     * @return bool
     */
    public function hasGroupStack()
    {
        return ! empty($this->groupStack);
    }

    /**
     * Get the menu's MenuItemCollection.
     *
     * @return MenuItemCollection
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getMenuItems()->toArray();
    }
}
