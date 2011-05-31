# Fuel-Twtmore
This handy FuelPHP Package allows you to fully integrate with the [twtmore](http://twtmore.com) API with ease.

## How to install
To install this package, simply add the following source to your `package.php` configuration file.

    http://github.com/twtmore

Then to install the package, run this command from the root of your project

    php oil package install twtmore

## Configuration
You'll need to have registered a twtmore Application at the twtmore [developer center](http://dev.twtmore.com/) before continuing.
To configure Fuel-Twtmore, just copy the `twtmore.php` config file in `fuel/packages/twtmore/config` to your local application config folder, and enter the API you generated from the developer center.

## Usage
The twtmore package is fully static, so you wont need to instantiate it anywhere.

### Tweet Method
Documentation: http://dev.twtmore.com/docs/api/tweet

```php
Twtmore::tweet('A'); // http://twtmore.com/tweet/A
```

### Shorten Method
Documentation: http://dev.twtmore.com/docs/api/shorten

```php
// Simple
Twtmore::shorten('twtmore', 'Long tweet...');

// In reply to
Twtmore::shorten('twtmore', 'Long tweet...', 'twtmoretest', '123123123123');
```

### Callback Method
Documentation: http://dev.twtmore.com/docs/api/callback

```php
Twtmore::callback('.. callback key ..', '12313123123);
```