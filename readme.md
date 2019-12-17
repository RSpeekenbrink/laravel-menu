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
Menu::add('itemName', '/');


// Menu::toArray() Output:
[
    [
        'name' => 'itemName',
        'route' => '/',
        'active' => true,
    ]
]
```

**The itemName should be unique within the menu since this is the identifier of the item in the Menu.**

### Route attribute and Active state

The route can be an absolute route like ```'/dashboard/profile'``` or a name of a route like ```'dashboard.index'``` for the automatic active state checking to work properly. If you want to use route names we recommend you to use [Ziggy](https://github.com/tightenco/ziggy) to convert the names to URLs in your front-end.

The active attribute is a boolean that will be true if the route matches the route of the current request (path or name wise). 

### Nested Routes

To create nested items you could use the following:

```php
Menu::add('dashboard', '/')->addChildren(function () {
    Menu::add('stats', '/stats');
    Menu::add('profile', '/profile');
});

// Menu::toArray() Output:
[
    [
        'name' => 'dashboard',
        'route' => '/',
        'active' => true,
        'children' => [
            [
                'name' => 'dashboard.stats',
                'route' => '/stats',
                'active' => false,
            ],
            [
                'name' => 'dashboard.profile',
                'route' => '/profile',
                'active' => false,
            ]
        ]
    ]
]
```

### Attributes

You can pass attributes to the MenuItem to define values like Title or anything else you desire;

```php
Menu::add('itemName', '/', ['title' => 'Dashboard', 'someAttribute' => 231, 'another' => 'value2']);


// Menu::toArray() Output:
[
    [
        'name' => 'itemName',
        'route' => '/',
        'active' => true,
        'title' => 'Dashboard',
        'someAttribute' => 231,
        'another' => 'value2,
    ]
]
```

### Adding items condition wise or via Auth Guards

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
    <nav>
        <template v-for="(item, i) in $page.menu">
             <template>
                 <li :class="item.active ? 'active' : ''"
                     @click="$inertia.visit(item.route, { preserveState: true })">
                     <span>{{ item.title }}</span>
                 </li>
             </template>
             <template v-if="item.children" v-for="(child, i) in item.children">
                 <li :class="child.active ? 'active' : ''"
                      @click="$inertia.visit(child.route, { preserveState: true })">
                      <span>{{ child.title }}</span>
                  </li>
             </template>
        </template>
    </nav>
</template>
```

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email contact@rspeekenbrink.nl instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
