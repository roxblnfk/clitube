<p align="center">
<img src="media/logo.svg" alt="CLI Tube">
</p>

<p align="center">
<a href="https://packagist.org/packages/clitube/clitube"><img src="https://poser.pugx.org/clitube/clitube/require/php"></a>
<a href="https://packagist.org/packages/clitube/clitube"><img src="https://poser.pugx.org/clitube/clitube/version"></a>
<a href="https://packagist.org/packages/clitube/clitube"><img src="https://poser.pugx.org/clitube/clitube/downloads"></a>
<a href="https://github.com/clitube/clitube/actions"><img src="https://github.com/clitube/clitube/workflows/phpunit/badge.svg"></a>
<a href="https://shepherd.dev/github/clitube/clitube"><img src="https://shepherd.dev/github/clitube/clitube/coverage.svg"></a>
<a href="https://shepherd.dev/github/clitube/clitube"><img src="https://shepherd.dev/github/clitube/clitube/level.svg"></a>
</p>

The package will help you to render paginated tables and any plain text content in a console.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+

## Installation

You can install the package via composer:

```bash
composer require clitube/clitube
```

## Examples

### Paginator Component

```php
$core = (new \CliTube\Core($output))
$core->createComponent(\CliTube\Component\Paginator::class, [
    new MyPaginator(), // Instanceof \CliTube\Data\Paginator
])
$core->run();
```

#### Navigation

![paginator navigation](media/pagination-navigation.gif)

#### A wide table scrolling

![paginator scrolling](media/pagination-scroll-horizontally.gif)

### Scroll Component

```php
$core = (new \CliTube\Core($output))
$core->createComponent(\CliTube\Component\Scroll::class, [
    'content' => 'Very long text',
    'overwrite' => true,
])
$core->run();
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
