<?php

namespace RSpeekenbrink\LaravelInertiaMenu;

use Illuminate\Contracts\Support\Arrayable;
use RSpeekenbrink\LaravelInertiaMenu\Contracts\MenuItem as MenuItemContract;

class MenuItem implements MenuItemContract, Arrayable
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $route;

    /**
     * MenuItem constructor.
     *
     * @param $title
     * @param $route
     */
    public function __construct($title, $route)
    {
        $this->title = $title;
        $this->route = $route;
    }

    /**
     * Get the title of the MenuItem.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title of the MenuItem.
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the route of the MenuItem.
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
        return [
            'title' => $this->getTitle(),
            'route' => $this->getRoute(),
        ];
    }
}
