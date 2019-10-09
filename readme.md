# Laravel Menu

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rspeekenbrink/laravel-menu.svg?style=flat-square)](https://packagist.org/packages/rspeekenbrink/laravel-menu)
[![Build Status](https://img.shields.io/travis/rspeekenbrink/laravel-menu/master.svg?style=flat-square)](https://travis-ci.org/rspeekenbrink/laravel-menu)
[![Total Downloads](https://img.shields.io/packagist/dt/rspeekenbrink/laravel-menu.svg?style=flat-square)](https://packagist.org/packages/rspeekenbrink/laravel-menu)

Create menu objects server-sided without sweat for Front-End adoption.

## Installation

You can install the package via composer:

```bash
composer require rspeekenbrink/laravel-menu
```

## Usage

A default menu will already be registered and bound to the `Menu` facade. You can add items to the menu like this:

```php
Menu::add('itemName', ['link' => '/', 'title' => 'Home']);


// Menu::toArray() Output:
[
    {
        'name' => 'itemName',
        'title' => 'Home,
        'link' => '/,
    }
]
```

The itemName should be unique within the menu since this is the identifier of the item in the Menu.


To create nested items you could use the following:

```php
Menu::add('dashboard', ['title' => 'Dashboard'])->addChildren(function () {
    Menu::add('index', ['link' => '/', 'title' => 'Home']);
    Menu::add('profile', ['link' => '/profile', 'title' => 'Profile']);
});

// Menu::toArray() Output:
[
    {
        'name' => 'dashboard',
        'title' => 'Dashboard',
        'children' => [
            {
                'name' => 'dashboard.index',
                'title' => 'Home,
                'link' => '/',
            },
            {
                'name' => 'dashboard.profile',
                'title' => 'Profile,
                'link' => '/profile',
            }
        ]
    }
]
```

You can pass any attributes to the MenuItem.

### Usage with InertiaJS

The main purpose of this package is to create Menu objects that can be adopted easily by the Front-End.
One of the easiest ways to transfer the objects from the back to the front is by using [InertiaJS](https://inertiajs.com/).

```php
Inertia::share([
    'menu' => function () {
        return Menu::toArray();
    }
]);
```

Then for example in your inertia-vue layout template;

```vue
<template>
    <template
        v-for="(item, i) in $page.menu"
    >
        <template v-if="item.children"
            v-for="(child, childIndex) in item.children"
        >
            <inertia-link
               :href="child.link"
               :class="child.active ? 'v-list-item--active' : ''"
              >
                child.title
             </inertia-link>
        </template>
    
        <inertia-link
           v-else
           :href="item.link"
           :class="item.active ? 'v-list-item--active' : ''"
          >
            item.title
         </inertia-link>
    </template>
</template>
```

### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email contact@rspeekenbrink.nl instead of using the issue tracker.

## Credits

- [RSpeekenbrink](https://github.com/rspeekenbrink)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
