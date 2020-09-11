<?php

namespace RSpeekenbrink\LaravelMenu;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use JsonSerializable;

class MenuItem implements Arrayable, Jsonable, JsonSerializable
{
    use HasAttributes, IsAssociatedWithMenu;

    /** @var string */
    protected $name;

    /** @var string */
    protected $route;

    /** @var MenuItemCollection */
    protected $children;

    /** @var array */
    protected $guardedAttributes = [
        'active',
        'children',
        'menu',
        'name',
        'route',
    ];

    /**
     * MenuItem constructor.
     *
     * @param string $name
     * @param string $route
     * @param Menu $menu
     * @param array $attributes
     */
    public function __construct(string $name, string $route, Menu $menu, $attributes = [])
    {
        $this->initializeItem($name, $route, $menu);

        $this->fill($attributes);
    }

    /**
     * Initialize the values of the MenuItem.
     *
     * @param string $name
     * @param string $route
     * @param Menu $menu
     */
    protected function initializeItem(string $name, string $route, Menu $menu)
    {
        $this->setMenu($menu);
        $this->setName($name);
        $this->setRoute($route);

        $this->children = new MenuItemCollection();
    }

    /**
     * Fill the menuItem with the given attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    /**
     * Convert some of the variables of the MenuItem to array.
     *
     * @return array
     */
    protected function variablesToArray()
    {
        $array = [
            'name' => $this->getName(),
            'route' => $this->getRoute(),
            'active' => $this->isActive(),
        ];

        if (count($this->getChildren()) > 0) {
            $array['children'] = $this->getChildren()->toArray();
        }

        return $array;
    }

    /**
     * Get an attribute array of all arrayable attributes.
     *
     * @return array
     */
    protected function getArrayableAttributes()
    {
        return $this->attributes;
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
        $this->children->add($item);

        return $this;
    }

    /**
     * Add multiple children to the MenuItem.
     *
     * @param Closure $items
     * @return $this
     */
    public function addChildren(Closure $items)
    {
        $this->menu->loadChildren($this, $items);

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
     * Set the route of the menuItem.
     *
     * @param string $route
     * @return $this
     */
    public function setRoute(string $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Returns the route of the menuItem.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Return the attributes of the MenuItem as array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->attributesToArray(), $this->variablesToArray());
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

    /**
     * Determine if the given attribute may be mass assigned.
     *
     * @param  string  $key
     * @return bool
     */
    public function isFillable($key)
    {
        return !in_array($key, $this->guardedAttributes);
    }

    /**
     * Returns if menuItem is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return Request::is($this->getRoute()) || (Route::currentRouteName() == $this->getRoute());
    }
}
