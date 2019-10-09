<?php

namespace RSpeekenbrink\LaravelMenu;

use Illuminate\Support\Collection;

class MenuItemCollection extends Collection
{
    /**
     * Returns if the collection has a MenuItem with the given name.
     *
     * @param string $name
     * @return bool
     */
    public function hasName(string $name)
    {
        return $this->filter(function (MenuItem $item) use ($name) {
            return $item->getName() == $name ?: $item->getChildren()->hasName($name);
        })->count() > 0;
    }

    /**
     * Get a MenuItem from the collection by name.
     *
     * @param string $name
     * @return MenuItem
     */
    public function getItemByName(string $name)
    {
        if ($item = $this->filter(function (MenuItem $item) use ($name) {
            return $item->getName() == $name;
        })->first()) {
            return $item;
        }

        // Nested Search
        foreach ($this as $item) {
            if ($item instanceof MenuItem) {
                if ($foundItem = $item->getChildren()->getItemByName($name)) {
                    return $foundItem;
                }
            }
        }
    }
}
