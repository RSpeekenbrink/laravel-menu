<?php

namespace RSpeekenbrink\LaravelMenu;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;
use RSpeekenbrink\LaravelMenu\Contracts\MenuItem as MenuItemContract;

class MenuItem implements MenuItemContract, Arrayable
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $title;

    /** @var string */
    protected $link;

    /** @var bool */
    protected $active = false;

    /** @var MenuItemCollection */
    protected $children;

    /** @var array */
    protected $allowedAttributes = [
        'title', 'link', 'active',
    ];

    /**
     * MenuItem constructor.
     *
     * @param string $name
     * @param array $attributes
     */
    public function __construct($name, $attributes = [])
    {
        $this->name = $name;
        $this->children = new MenuItemCollection();

        $this->setAttributes($attributes);
    }

    /**
     * Set the attributes of the menu item.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($this->allowedAttributes as $allowedAttribute) {
            if (Arr::exists($attributes, $allowedAttribute)) {
                $this->{ $allowedAttribute } = Arr::get($attributes, $allowedAttribute);
            }
        }
    }

    /**
     * Get the MenuItems attributes as array.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];

        foreach ($this->allowedAttributes as $allowedAttribute) {
            $attributes[$allowedAttribute] = $this->{$allowedAttribute};
        }

        return $attributes;
    }

    /**
     * Get the name of the menu.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get the link of the MenuItem.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set the link of the MenuItem.
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Returns if the item has been marked as active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Sets the active state of the MenuItem.
     *
     * @param bool $state
     * @return $this
     */
    public function setActive(bool $state)
    {
        $this->active = $state;

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
     * @param MenuItemContract $item
     * @return $this
     */
    public function addChild(MenuItemContract $item)
    {
        $this->getChildren()->add($item);

        return $this;
    }

    /**
     * Return the attributes of the MenuItem as array.
     *
     * @return array
     */
    public function toArray()
    {
        $item = [
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'link' => $this->getLink(),
            'active' => $this->isActive(),
        ];

        if (count($this->getChildren()) > 0) {
            $item['children'] = $this->getChildren()->toArray();
        }

        return $item;
    }
}
