<?php

namespace RSpeekenbrink\LaravelInertiaMenu;

use RSpeekenbrink\LaravelInertiaMenu\Contracts\MenuItem as MenuItemContract;

class MenuItemGroup extends MenuItem
{
    /** @var string */
    protected $namespace;

    /** @var MenuItemCollection */
    protected $children;

    public function __construct($namespace, $title = null)
    {
        $this->namespace = $namespace;
        $this->children = new MenuItemCollection();

        parent::__construct($title, null);
    }

    /**
     * Add child item to the group.
     *
     * @param MenuItemContract $item
     * @return MenuItemContract
     */
    public function addChild(MenuItemContract $item)
    {
        $this->children->add($item);

        return $item;
    }

    /**
     * Get the Children of the MenuItemGroup.
     *
     * @return MenuItemCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get the MenuItemGroups Namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
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
          'children' => $this->children->toArray(),
        ];
    }
}
