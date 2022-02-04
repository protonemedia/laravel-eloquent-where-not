# Laravel Eloquent Where Not

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-eloquent-where-not.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-eloquent-where-not)
![run-tests](https://github.com/protonemedia/laravel-eloquent-where-not/workflows/run-tests/badge.svg)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-eloquent-where-not.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-eloquent-where-not)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-eloquent-where-not.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-eloquent-where-not)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/protonemedia/laravel-eloquent-where-not)

### 📺 Want to see this package in action? Join the live stream on December 10 at 13:30 CET: [https://youtu.be/fAY75SLQj3w](https://youtu.be/fAY75SLQj3w)

## Requirements

* PHP 7.4+
* Laravel 8.0 or 9.0

This package is tested with GitHub Actions using MySQL 5.7, PostgreSQL 10.8 and SQLite.

## Features

* Flip/invert your scope, or really any query constraint.
* Zero third-party dependencies.

Related package: [Laravel Eloquent Scope as Select](https://github.com/protonemedia/laravel-eloquent-scope-as-select)

## Support

We proudly support the community by developing Laravel packages and giving them away for free. Keeping track of issues and pull requests takes time, but we're happy to help! If this package saves you time or if you're relying on it professionally, please consider [supporting the maintenance and development](https://github.com/sponsors/pascalbaljet).

## Blogpost

If you want to know more about the background of this package, please read the blogpost: [Apply the opposite of your Eloquent scope to the Query Builder with a Laravel package](https://protone.media/blog/apply-the-opposite-of-your-eloquent-scope-to-the-query-builder-with-a-laravel-package).

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-eloquent-where-not
```

Add the `macro` to the query builder, for example, in your `AppServiceProvider`. By default, the name of the macro is `whereNot`, but you can customize it with the first parameter of the `addMacro` method.

```php
use ProtoneMedia\LaravelEloquentWhereNot\WhereNot;

public function boot()
{
    WhereNot::addMacro();

    // or use a custom method name:
    WhereNot::addMacro('not');
}
```

## Short API description

*For a more practical explanation, check out the [usage](#usage) section below.*

Call the `whereNot` method with a Closure:
```php
Post::whereNot(function ($query) {
    $query->onFrontPage();
})->get();
```

The example above can be shortened by using a string, which should be the name of the scope:
```php
Post::whereNot('onFrontPage')->get();
```

You can use an array to call multiple scopes:
```php
Post::whereNot(['popular', 'published'])->get();
```

Use an associative array to call dynamic scopes:
```php
Post::whereNot(['ofType' => 'announcement'])->get();
```

If your dynamic scopes require multiple arguments, you can use an associative array:
```php
Post::whereNot(['publishedBetween' => [2010, 2020]])->get();
```

You can also mix dynamic and non-dynmaic scopes:
```php
Post::whereNot([
    'published',
    'ofType' => 'announcement'
])->get();
```

## Usage

Imagine you have a `Post` Eloquent model with a query scope that constraints the query to all posts that should make the front page.

```php
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeOnFrontPage($query)
    {
        $query->where('is_public', 1)
            ->where('votes', '>', 100)
            ->has('comments', '>=', 20)
            ->whereHas('user', fn($user) => $user->isAdmin())
            ->whereYear('published_at', date('Y'));
    }
}
```

Now you can fetch all posts for your front page by calling the scope method on the query:

```php
$posts = Post::onFrontPage()->get();
```

But what if you want to fetch *all* posts that *didn't* make the front page? Using the power of this package, you can re-use your scope!

```php
$posts = Post::whereNot(function($query) {
    $query->onFrontPage();
})->get();
```

With short closures, a feature which was introduced in PHP 7.4, this can be even shorter:

```php
$posts = Post::whereNot(fn ($query) => $query->onFrontPage())->get();
```

### Shortcuts

Instead of using a Closure, there are some shortcuts you could use (see also: [Short API description](#short-api-description)):

Using a string instead of a Closure:

```php
Post::whereNot(function ($query) {
    $query->published();
});

// is the same as:

Post::whereNot('published');
```

Using an array instead of Closure, to support multiple scopes and dynamic scopes:

```php
Post::whereNot(function ($query) {
    $query->ofType('announcement');
});

// is the same as:

Post::whereNot(['ofType' => 'announcement']);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other Laravel packages

* [`Laravel Analytics Event Tracking`](https://github.com/protonemedia/laravel-analytics-event-tracking): Laravel package to easily send events to Google Analytics.
* [`Laravel Blade On Demand`](https://github.com/protonemedia/laravel-blade-on-demand): Laravel package to compile Blade templates in memory.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel Form Components`](https://github.com/protonemedia/laravel-form-components): Blade components to rapidly build forms with Tailwind CSS Custom Forms and Bootstrap 4. Supports validation, model binding, default values, translations, includes default vendor styling and fully customizable!
* [`Laravel Paddle`](https://github.com/protonemedia/laravel-paddle): Paddle.com API integration for Laravel with support for webhooks/events.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel WebDAV`](https://github.com/protonemedia/laravel-webdav): WebDAV driver for Laravel's Filesystem.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/pascalbaljetmedia/laravel-eloquent-where-not) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
