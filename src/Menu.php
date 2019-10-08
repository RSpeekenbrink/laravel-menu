<?php

namespace RSpeekenbrink\LaravelMenu;

use JsonSerializable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use RSpeekenbrink\LaravelMenu\Exceptions\NameExistsException;

class Menu implements Arrayable, Jsonable, JsonSerializable
{
    /** @var MenuItemCollection */
    protected $menuItems;

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

        $item = $this->createItem($name, $attributes);

        $this->menuItems->add($item);

        return $item;
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
        $item = $this->newItem($name, $attributes);

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
