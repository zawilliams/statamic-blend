# Blend for Statamic ![Statamic 2.11](https://img.shields.io/badge/statamic-2.11-blue.svg?style=flat-square)

Use Laravel Mix with Statamic in Blade templates like you already do with Laravel. It uses the [Mix](https://github.com/laravel/framework/blob/5.8/src/Illuminate/Foundation/Mix.php) class from Laravel as a singleton with some Statamic adaptations for finding theme files. This Addon works with Blade templates only. If you're using Antlers, check out [Statamic Mix](https://statamic.com/marketplace/addons/statamic-mix).

## Installation

Simply copy the `Blend` folder into `site/addons/`. That's it!

## Usage

Just use it like you would normally use [Mix](https://laravel-mix.com/docs/4.0/workflow) with Laravel Blade templates:

```html
<!DOCTYPE html>
<html>
    <head>
        <title>Statamic</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
```

You can also change your manifest directory just like you can with Laravel:

```html
<link rel="stylesheet" href="{{ mix('css/app.css', 'public/build') }}">
```

## Acknowledgements

- Thanks to [Ben Furfie](https://github.com/benfurfie) for some code in [Statamic Mix](https://github.com/benfurfie/statamic-mix-version)
