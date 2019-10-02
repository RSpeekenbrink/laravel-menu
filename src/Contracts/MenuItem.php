<?php

namespace RSpeekenbrink\LaravelInertiaMenu\Contracts;

interface MenuItem
{
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
     * @return self
     */
    public function setTitle($title);

    /**
     * Get the route of the MenuItem.
     *
     * @return string
     */
    public function getRoute();

    /**
     * Return the attributes of the MenuItem as array.
     *
     * @return array
     */
    public function toArray();
}
