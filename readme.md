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
Menu::add('itemName', '/', ['title' => 'Home']);


// Menu::toArray() Output:
[
    [
        'name' => 'itemName',
        'title' => 'Home,
        'route' => '/',
        'active' => true, //depending on current request
    ]
]
```

The itemName should be unique within the menu since this is the identifier of the item in the Menu.


To create nested items you could use the following:

```php
Menu::add('dashboard', '/', ['title' => 'Dashboard'])->addChildren(function () {
    Menu::add('stats', '/stats', ['title' => 'Home']);
    Menu::add('profile', '/profile', ['title' => 'Profile']);
});

// Menu::toArray() Output:
[
    [
        'name' => 'dashboard',
        'title' => 'Dashboard',
        'route' => '/',
        'active' => true,
        'children' => [
            [
                'name' => 'dashboard.index',
                'title' => 'Home,
                'route' => '/stats',
                'active' => false,
            ],
            [
                'name' => 'dashboard.profile',
                'title' => 'Profile,
                'route' => '/profile',
                'active' => false,
            ]
        ]
    ]
]
```

You can pass any attributes to the MenuItem.

```php
Menu::add('itemName', '/', ['someAttribute' => 231, 'another' => 'value2']);


// Menu::toArray() Output:
[
    [
        'name' => 'itemName',
        'route' => '/',
        'active' => true,
        'someAttribute' => 231,
        'another' => 'value2,
    ]
]
```

### Adding items condition wise

If you would like to add menu items conditionwise, for example only add a menu item if a user is logged in, you can do it like this:

```php
Menu::addIf($conditionOrClosure, 'itemName', $route, $attributes);
```

Or pass a Auth Guard:

```php
Menu::addIfCan('MyAuthGuard', 'itemName', $route, $attributes);
```

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
               :href="child.route"
               :class="child.active ? 'v-list-item--active' : ''"
              >
                child.title
             </inertia-link>
        </template>
    
        <inertia-link
           v-else
           :href="item.route"
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
