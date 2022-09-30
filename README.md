<p align="center">
<img src="logo.svg" alt="CLI Tube">
</p>

<p align="center">
<a href="https://packagist.org/packages/roxblnfk/clitube"><img src="https://poser.pugx.org/roxblnfk/clitube/require/php"></a>
<a href="https://packagist.org/packages/roxblnfk/clitube"><img src="https://poser.pugx.org/roxblnfk/clitube/version"></a>
<a href="https://packagist.org/packages/roxblnfk/clitube"><img src="https://poser.pugx.org/roxblnfk/clitube/downloads"></a>
<a href="https://github.com/roxblnfk/clitube/actions"><img src="https://github.com/roxblnfk/clitube/workflows/phpunit/badge.svg"></a>
<a href="https://shepherd.dev/github/roxblnfk/clitube"><img src="https://shepherd.dev/github/roxblnfk/clitube/coverage.svg"></a>
<a href="https://shepherd.dev/github/roxblnfk/clitube"><img src="https://shepherd.dev/github/roxblnfk/clitube/level.svg"></a>
</p>

The package will help you to render paginated tables and any plain text content in a console.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+

## Installation

You can install the package via composer:

```bash
composer require roxblnfk/clitube
```

## Examples

### Paginator Component

```php
$core = (new \Roxblnfk\CliTube\Core($output))
$core->createComponent(\Roxblnfk\CliTube\Component\Paginator::class, [
    new MyPaginator(), // Instanceof \Roxblnfk\CliTube\Data\Paginator
])
$core->run();
```

### Scroll Component

```php
$core = (new \Roxblnfk\CliTube\Core($output))
$core->createComponent(\Roxblnfk\CliTube\Component\Scroll::class, [
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
