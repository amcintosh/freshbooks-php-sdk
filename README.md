# FreshBooks PHP SDK

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/amcintosh/freshbooks-php-sdk?style=flat)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/amcintosh/freshbooks-php-sdk/Run%20Tests)](https://github.com/amcintosh/freshbooks-php-sdk/actions?query=workflow%3A%22Run+Tests%22)

A FreshBooks PHP SDK to allow you to more easily utilize the [FreshBooks API](https://www.freshbooks.com/api).

## Installation

Install it via Composer.

```shell
composer require amcintosh/freshbooks-php-sdk
```

Requires a [PSR-18 implementation](https://packagist.org/providers/psr/http-client-implementation) client. If you do
not already have a compatible client, you can install one with it.

```shell
composer require amcintosh/freshbooks-php-sdk php-http/guzzle7-adapter
```

## Usage

### Configuring the API client

You can create an instance of the API client in one of two ways:

- By providing your application's OAuth2 `clientId` and `clientSecret` and following through the auth flow, which
  when complete will return an access token.
- Or if you already have a valid access token, you can instantiate the client with that token, however token refresh
  flows will not function without the application id and secret.

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

TODO: finish flow
Once the user has been redirected to your `redirectUri` and you have obtained the access grant code, you can exchange
that code for a valid access token.

### Current User

TODO: current user

### Making API Calls

Each resource in the client has provides calls for `get`, `list`, `create`, `update` and `delete` calls. Please note
that some API resources are scoped to a FreshBooks `accountId` while others are scoped to a `businessId`. In general
these fall along the lines of accounting resources vs projects/time tracking resources, but that is not precise.

```php
$client = $freshBooksClient->clients()->get($accountId, $clientId);
$project = $freshBooksClient->projects()->get($businessId, $projectId);
```

#### Get and List

API calls which return a single resource return a [DataTransferObject](https://github.com/spatie/data-transfer-object)
with the returned data accessible via properties.

```php
$client = $freshBooksClient->clients()->get($accountId, $clientId);

echo $client->organization; // 'FreshBooks'
$client->only('organization')->toArray(); // ['organization' => 'FreshBooks'];
```

`visState` numbers correspond with various states. See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
for details.

```php
use amcintosh\FreshBooks\Model\VisState;

echo $client->visState; // '0'
echo $client->visState == VisState::ACTIVE ? 'Is Active' : 'Not Active'; // 'Is Active'
```

API calls which return a list of resources return a [DataTransferObject](https://github.com/spatie/data-transfer-object)
with an array of the resources.

```php
$clients = $freshBooksClient->clients()->list($accountId);

echo $clients->clients[0]->organization; // 'FreshBooks'

foreach ($clients->clients as $client) {
    echo $client->organization;
}
```

#### Create, Update, and Delete

API calls to create and update take either a `DataModel` object, or an array of the resource data. A successful call
will return a `DataTransferObject` object as if a `get` call.

Create:

```php
$clientData = new Client();
$clientData->organization = 'FreshBooks';

$newClient = $freshBooksClient->clients()->create($accountId, model: $clientData));

echo $newClient->organization; // 'FreshBooks'
```

or

```php
$clientData = array('organization' => 'FreshBooks');

$newClient = $freshBooksClient->clients()->create($accountId, data: $clientData));

echo $newClient->organization; // 'FreshBooks'
```

Update:

```php
$clientData->organization = 'New Org';

$newClient = $freshBooksClient->clients()->update($accountId, $clientData->id, model: $clientData));

echo $newClient->organization; // 'New Org'
```

or

```php
$clientData = array('organization' => 'Really New Org');

$newClient = $freshBooksClient->clients()->update($accountId, $clientId, data: $clientData));

echo $newClient->organization; // 'Really New Org'

```

TODO: Delete
Delete:

```python
client = freshBooksClient.clients.delete(account_id, client_id)

assert client.vis_state == VisState.DELETED
```

#### Error Handling

Calls made to the FreshBooks API with a non-2xx response are wrapped in a `FreshBooksException`.
This exception class contains the error message, HTTP response code, FreshBooks-specific error number if one exists,
and the HTTP response body.

Example:

```php
use amcintosh\FreshBooks\Exception\FreshBooksException;

try {
    $client = $freshBooksClient->clients()->get($accountId, 134);
} catch (FreshBooksException $e) {
    echo $e->getMessage(); // 'Client not found'
    echo $e->getCode(); // 404
    echo $e->getErrorCode(); // 1012
    echo $e->getRawResponse(); // '{"response": {"errors": [{"errno": 1012,
                               // "field": "userid", "message": "Client not found.",
                               // "object": "client", "value": "134"}]}}'
}
```

TODO: this
Not all resources have full CRUD methods available. For example expense categories have `list` and `get`
calls, but are not deletable. If you attempt to call a method that does not exist, the SDK will raise a
`FreshBooksNotImplementedError` exception, but this is not something you will likely have to account
for outside of development.

## Development

### Testing

To run all tests:

```bash
make test
```

### Documentations

You can generate the documentation via:

```bash
make generate-docs
```
