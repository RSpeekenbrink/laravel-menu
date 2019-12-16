<?php

namespace RSpeekenbrink\LaravelMenu;

trait IsAssociatedWithMenu
{
    /** @var Menu */
    protected $menu;

    /**
     * Get the menu instance associated with the class.
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the menu instance associated with the class.
     *
     * @param Menu $menu
     * @return $this
     */
    protected function setMenu(Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }
}
