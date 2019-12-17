<?php

namespace RSpeekenbrink\LaravelMenu;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use RSpeekenbrink\LaravelMenu\Exceptions\NameExistsException;

class Menu implements Arrayable, Jsonable, JsonSerializable
{
    /** @var MenuItemCollection */
    protected $menuItems;

    /** @var array */
    protected $parentStack;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->menuItems = new MenuItemCollection();
    }

    /**
     * Add a new MenuItem to the menu.
     *
     * @param string $name
     * @param string $route
     * @param array $attributes
     * @return MenuItem
     *
     * @throws NameExistsException
     */
    public function add(string $name, string $route, array $attributes = [])
    {
        if ($this->hasParent()) {
            $name = end($this->parentStack)->getName().'.'.$name;
        }

        if ($this->menuItems->hasName($name)) {
            throw new NameExistsException($name);
        }

        $item = $this->createItem($name, $route, $attributes);

        $this->pushItem($item);

        return $item;
    }

    /**
     * Push the given item to the correct stacks.
     *
     * @param MenuItem $item
     * @return MenuItemCollection
     */
    protected function pushItem(MenuItem $item)
    {
        if ($this->hasParent()) {
            return end($this->parentStack)->addChild($item);
        }

        return $this->menuItems->add($item);
    }

    /**
     * Check if there is a parent in the parentstack.
     *
     * @return bool
     */
    protected function hasParent()
    {
        return ! empty($this->parentStack);
    }

    /**
     * Add a new menuItem to the menu if the condition is true.
     *
     * @param mixed $condition
     * @param string $name
     * @param string $route
     * @param array $attributes
     * @return MenuItem
     *
     * @throws NameExistsException
     */
    public function addIf($condition, string $name, string $route, array $attributes = [])
    {
        return $this->resolveCondition($condition) ? $this->add($name, $route, $attributes) : null;
    }

    /**
     * Add a new menuItem to the menu when authorized.
     *
     * @param string|array $authorization
     * @param string $name
     * @param string $route
     * @param array $attributes
     * @return MenuItem
     *
     * @throws NameExistsException
     */
    public function addIfCan($authorization, string $name, string $route, array $attributes = [])
    {
        $arguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($arguments);

        return $this->addIf(app(Gate::class)->allows($ability, $arguments), $name, $route, $attributes);
    }

    /**
     * Resolve the condition.
     *
     * @param $condition
     * @return bool
     */
    protected function resolveCondition($condition)
    {
        return is_callable($condition) ? $condition() : $condition;
    }

    /**
     * Create new MenuItem instance.
     *
     * @param string $name
     * @param string $route
     * @param array $attributes
     * @return MenuItem
     */
    protected function createItem(string $name, string $route, array $attributes = [])
    {
        $item = $this->newItem($name, $route, $attributes);

        return $item;
    }

    /**
     * Create new MenuItem object.
     *
     * @param string $name
     * @param string $route
     * @param array $attributes
     * @return MenuItem
     */
    protected function newItem(string $name, string $route, array $attributes = [])
    {
        return new MenuItem($name, $route, $this, $attributes);
    }

    /**
     * Find and return a MenuItem by name.
     *
     * @param $name
     * @return MenuItem
     */
    public function getItemByName($name)
    {
        return $this->menuItems->getItemByName($name);
    }

    /**
     * Get an index of an item by name.
     *
     * @param string $name
     * @return int
     */
    public function getIndexByName($name)
    {
        return $this->menuItems->getIndexByName($name);
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

    /**
     * @param MenuItem $parent
     * @param Closure $items
     */
    public function loadChildren(MenuItem $parent, Closure $items)
    {
        $this->parentStack[] = $parent;

        $items();

        array_pop($this->parentStack);
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
