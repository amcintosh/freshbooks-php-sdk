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

## Usage

### Configuring the API client

You can create an instance of the API client by passing a configuration object and your application's client_id.

To get a working client, you will need to:

- Provide your application's OAuth2 `clientId` and `clientSecret` and following through the auth flow, which when complete will return an access token
- Or if you already have a valid access token, you can instantiate the client directly using that token, however token refresh flows will not function without the application id and secret.

```php
<?php
use amcintosh\FreshBooks\Client;
use amcintosh\FreshBooks\ClientConfig;

$conf = new ClientConfig(
    clientSecret: '<your application secret>',
    redirectUri: = '<your redirect uri>',
);
$client = new Client('<your application id>', $conf);
```

and then proceed with the auth flow (see below).

Or

```php
$conf = new ClientConfig(
    accessToken: '<a valid token>'
);
$client = new Client('<your application id>', $conf);
```

For PHP 7.4, named arguments are not supported, so you will need to configure the SDK with an array of
key-values matching the argument names and values of the matching allowed types. For example:

```php
$client = new Client('<your application id>', [
    'clientSecret' => '<your application secret>',
    'redirectUri' => '<your redirect uri>',
]);
```

This method is otherwise discouraged because you lose out on type hinting.

## Development

### Testing

To run all tests:

```bash
make test
```
