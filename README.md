# This is my package clitube

[![PHP](https://img.shields.io/packagist/php-v/roxblnfk/clitube.svg?style=flat-square)](https://packagist.org/packages/roxblnfk/clitube)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/roxblnfk/clitube.svg?style=flat-square)](https://packagist.org/packages/roxblnfk/clitube)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/roxblnfk/clitube/run-tests?label=tests&style=flat-square)](https://github.com/roxblnfk/clitube/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/roxblnfk/clitube.svg?style=flat-square)](https://packagist.org/packages/roxblnfk/clitube)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+

## Installation

You can install the package via composer:

```bash
composer require roxblnfk/clitube
```

## Examples

### Paginator

```php
$core = (new \Roxblnfk\CliTube\Core($output))
$core->createComponent(\Roxblnfk\CliTube\Component\Paginator::class, [
    new MyPaginator(), // Instanceof \Roxblnfk\CliTube\Data\Paginator
])
$core->run();
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
