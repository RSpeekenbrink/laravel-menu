<?php

namespace RSpeekenbrink\LaravelInertiaMenu;

use Illuminate\Support\Collection;

class MenuItemCollection extends Collection
{
    /**
     * Get MenuItemGroup by namespace.
     *
     * @param string $namespace
     * @return MenuItemGroup
     */
    public function getGroupByNamespace($namespace)
    {
        return $this->whereInstanceOf(MenuItemGroup::class)->filter(function ($item) use ($namespace) {
            return $item->getNamespace() == $namespace;
        })->first();
    }
}
