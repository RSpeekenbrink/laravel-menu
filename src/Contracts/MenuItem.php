<?php

namespace RSpeekenbrink\LaravelMenu\Contracts;

use RSpeekenbrink\LaravelMenu\MenuItemCollection;

interface MenuItem
{
    /**
     * MenuItem constructor.
     *
     * @param string $name
     * @param array $attributes
     */
    public function __construct($name, $attributes);

    /**
     * Set the attributes of the menu item.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * Get the MenuItems attributes as array.
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get the name of the menu.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the title of the MenuItem.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set the title of the MenuItem.
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get the link of the MenuItem.
     *
     * @return string
     */
    public function getLink();

    /**
     * Set the link of the MenuItem.
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link);

    /**
     * Returns if the item has been marked as active.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Sets the active state of the MenuItem.
     *
     * @param bool $state
     * @return $this
     */
    public function setActive(bool $state);

    /**
     * Get the children of the MenuItem.
     *
     * @return MenuItemCollection
     */
    public function getChildren();

    /**
     * Add children to the MenuItem.
     *
     * @param MenuItem $item
     * @return $this
     */
    public function addChild(self $item);

    /**
     * Return the attributes of the MenuItem as array.
     *
     * @return array
     */
    public function toArray();
}
