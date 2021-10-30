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

You can create an instance of the API client in one of two ways:

- By providing your application's OAuth2 `clientId` and `clientSecret` and following through the auth flow, which when complete will return an access token.
- Or if you already have a valid access token, you can instantiate the client with that token, however token refresh flows will not function without the application id and secret.

```php
use amcintosh\FreshBooks\FreshBooksClient;
use amcintosh\FreshBooks\FreshBooksClientConfig;

$conf = new FreshBooksClientConfig(
    clientSecret: 'your secret',
    redirectUri: 'https://some-redirect',
);

$freshBooksClient = new FreshBooksClient('your application id', $conf);
```

and then proceed with the auth flow (see below).

Or

```php
use amcintosh\FreshBooks\FreshBooksClient;
use amcintosh\FreshBooks\FreshBooksClientConfig;

$conf = new FreshBooksClientConfig(
    accessToken: 'a valid token',
);

$freshBooksClient = new FreshBooksClient('your application id', $conf);
```

#### Authoization flow

_This is a brief summary of the OAuth2 authorization flow and the methods in the FreshBooks API Client
around them. See the [FreshBooks API - Authentication](https://www.freshbooks.com/api/authentication) documentation._

First, instantiate your Client with `clientId`, `clientSecret`, and `redirectUri` as above.

To get an access token, the user must first authorize your application. This can be done by sending the user to
the FreshBooks authorization page. Once the user has clicked accept there, they will be redirected to your
`redirectUri` with an access grant code. The authorization URL can be obtained by calling
`$freshBooksClient->getAuthRequestUrl()`. This method also accepts a list of scopes that you wish the user to
authorize your application for.

```php
$authUrl = $freshBooksClient->getAuthRequestUrl(['user:profile:read', 'user:clients:read'])
```

Once the user has been redirected to your `redirectUri` and you have obtained the access grant code, you can exchange that code for a valid access token.

### Current User

## Development

### Testing

To run all tests:

```bash
make test
```
