<?php

namespace RSpeekenbrink\LaravelMenu;

use Closure;
use JsonSerializable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
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
     * @param array $attributes
     * @return Contracts\MenuItem
     *
     * @throws NameExistsException
     */
    public function add(string $name, array $attributes = [])
    {
        if ($this->menuItems->hasName($name)) {
            throw new NameExistsException($name);
        }

        if ($this->hasParent()) {
            $name = end($this->parentStack)->getName().'.'.$name;
        }

        $item = $this->createItem($name, $attributes);

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
     * @param array $attributes
     * @return bool|Contracts\MenuItem
     *
     * @throws NameExistsException
     */
    public function addIf($condition, string $name, array $attributes = [])
    {
        return $this->resolveCondition($condition) ? $this->add($name, $attributes) : null;
    }

    /**
     * Add a new menuItem to the menu when authorized.
     *
     * @param string|array $authorization
     * @param string $name
     * @param array $attributes
     * @return null|Contracts\MenuItem
     *
     * @throws NameExistsException
     */
    public function addIfCan($authorization, string $name, array $attributes = [])
    {
        $arguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($arguments);

        return $this->addIf(app(Gate::class)->allows($ability, $arguments), $name, $attributes);
    }

    /**
     * Find and return a MenuItem by name.
     *
     * @param $name
     * @return null|Contracts\MenuItem
     */
    public function getItemByName($name)
    {
        return $this->menuItems->getItemByName($name);
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
     * @param array $attributes
     * @return Contracts\MenuItem
     */
    protected function createItem(string $name, array $attributes = [])
    {
        $item = $this->newItem($name, $attributes)->setMenu($this);

        return $item;
    }

    /**
     * Create new MenuItem object.
     *
     * @param string $name
     * @param array $attributes
     * @return MenuItem
     */
    protected function newItem(string $name, array $attributes = [])
    {
        return new MenuItem($name, $attributes);
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
     * Get an index of an item by name.
     *
     * @param string $name
     * @return int
     */
    public function getIndexByName($name)
    {
        return $this->menuItems->search(function (MenuItem $item) use ($name) {
            return $item->getName() == $name;
        });
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
