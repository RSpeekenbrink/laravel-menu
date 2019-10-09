<?php

namespace RSpeekenbrink\LaravelMenu;

use Closure;
use Illuminate\Database\Eloquent\MassAssignmentException;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use RSpeekenbrink\LaravelMenu\Exceptions\MissingAssociatedMenuException;

class MenuItem implements Arrayable, Jsonable, JsonSerializable
{
    use HasAttributes, GuardsAttributes;

    /** @var string */
    protected $name;

    /** @var MenuItemCollection */
    protected $children;

    /** @var Menu */
    protected $menu;

    /** @var array */
    protected $guardedAttributes = [
        'children',
        'menu',
        'name',
    ];

    /**
     * MenuItem constructor.
     *
     * @param string $name
     * @param array $attributes
     */
    public function __construct($name, $attributes = [])
    {
        $this->guard($this->guardedAttributes);

        $this->initializeItem($name);

        $this->fill($attributes);
    }

    /**
     * Initialize the values of the MenuItem.
     *
     * @param $name
     */
    protected function initializeItem($name)
    {
        $this->name = $name;
        $this->children = new MenuItemCollection();
    }

    /**
     * Fill the menuItem with the given attributes.
     *
     * @param array $attributes
     * @return $this
     *
     * @throws MassAssignmentException
     */
    public function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * Get the name of the menuItem.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the menuItem.
     *
     * @param $name
     * @return $this
     */
    protected function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the children of the MenuItem.
     *
     * @return MenuItemCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add children to the MenuItem.
     *
     * @param self $item
     * @return $this
     */
    public function addChild(self $item)
    {
        $this->getChildren()->add($item);

        return $this;
    }

    /**
     * Add multiple children to the MenuItem.
     *
     * @param Closure $items
     * @return $this
     *
     * @throws MissingAssociatedMenuException
     */
    public function addChildren(Closure $items)
    {
        if (! $this->getMenu()) {
            throw new MissingAssociatedMenuException("For MenuItem: ".$this->getName());
        }

        $this->menu->loadChildren($this, $items);

        return $this;
    }

    /**
     * Get the menu instance associated with the menuItem.
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the menu instance associated with the MenuItem.
     *
     * @param Menu $menu
     * @return $this
     */
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Return the attributes of the MenuItem as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributesToArray();
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
