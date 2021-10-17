# FreshBooks PHP SDK

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/amcintosh/freshbooks-php-sdk?style=flat)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/amcintosh/freshbooks-php-sdk/Run%20Tests)](https://github.com/amcintosh/freshbooks-php-sdk/actions?query=workflow%3A%22Run+Tests%22)

A FreshBooks PHP SDK to allow you to more easily utilize the [FreshBooks API](https://www.freshbooks.com/api).

## Installation

Install it via Composer.

```shell
composer require amcintosh/freshbooks-php-sdk
```

Requires a [PSR-18 implementation](https://packagist.org/providers/psr/http-client-implementation) client. If you do not already have a compatible client, you can install one with it.

```shell
composer require amcintosh/freshbooks-php-sdk php-http/guzzle7-adapter
```

## Development

### Testing

To run all tests:

```bash
make test
```
