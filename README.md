# FreshBooks PHP SDK

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/amcintosh/freshbooks?style=flat)
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

Check out some of our [examples](examples/README.md).

This SDK makes use of the [spryker/decimal-object](https://packagist.org/packages/spryker/decimal-object) package.
All monetary amounts are represented as as `Spryker\DecimalObject\Decimal`, so it is recommended that you refer to
[their documentation](https://github.com/spryker/decimal-object/tree/master/docs).

```php
use Spryker\DecimalObject\Decimal;

$this->assertEquals(Decimal::create('41.94'), $invoice->amount->amount);
```

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

FreshBooks users are uniquely identified by their email across our entire product. One user may act on several
Businesses in different ways, and our Identity model is how we keep track of it. Each unique user has an Identity,
and each Identity has Business Memberships which define the permissions they have.

See [FreshBooks API - Business, Roles, and Identity](https://www.freshbooks.com/api/me_endpoint) and
[FreshBooks API - The Identity Model](https://www.freshbooks.com/api/identity_model).

The current user can be accessed by:

```php
$identity = $freshBooksClient->currentUser()
echo $identity.email // prints the current user's email

// Print name and role of each business the user is a member of
foreach ($identity.businessMemberships as $businessMembership) {
    echo $businessMembership->business.name
    echo $businessMembership->role; // eg. owner
}
```

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

_Note_: When using the array of data, you need to specify the field as it exists in the FreshBooks API. There
are API fields that are translated to more intuitive names in the data models. For example `fname` = `firstName`,
or `bus_phone` = `businessPhone`.

Create:

```php
$clientData = new Client();
$clientData->organization = 'FreshBooks';
$clientData->firstName = 'Gordon';
$clientData->businessPhone = '416-444-4445';

$newClient = $freshBooksClient->clients()->create($accountId, model: $clientData);

echo $newClient->organization; // 'FreshBooks'
echo $newClient->firstName; // 'Gordon'
echo $newClient->businessPhone; // '416-444-4445'
```

or

```php
$clientData = array(
    'organization' => 'FreshBooks',
    'fname' => 'Gordon',
    'bus_phone' => '416-444-4445'
);

$newClient = $freshBooksClient->clients()->create($accountId, data: $clientData);

echo $newClient->organization; // 'FreshBooks'
echo $newClient->firstName; // 'Gordon'
echo $newClient->businessPhone; // '416-444-4445'
```

Update:

```php
$clientData->organization = 'New Org';
$clientData->firstName = 'Gord';

$newClient = $freshBooksClient->clients()->update($accountId, $clientData->id, model: $clientData);

echo $newClient->organization; // 'New Org'
echo $newClient->firstName; // 'Gord'
```

or

```php
$clientData = array(
    'organization' => 'Really New Org',
    'fname' => 'Gord',
);

$newClient = $freshBooksClient->clients()->update($accountId, $clientId, data: $clientData);

echo $newClient->organization; // 'Really New Org'
echo $newClient->firstName; // 'Gord'

```

Delete:

```php
$client = $freshBooksClient->clients()->delete($accountId, $clientId);

echo $client->visState; // '1'
echo $client->visState == VisState::ACTIVE ? 'Is Active' : 'Not Active'; // 'Not Active'
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

#### Pagination, Filters, and Includes

`list` calls take a list of builder objects that can be used to paginate, filter, and include
optional data in the response. See [FreshBooks API - Parameters](https://www.freshbooks.com/api/parameters) documentation.

##### Pagination

Pagination results are included in `list` responses:

```php
$clients = $freshBooksClient->clients()->list($accountId);

echo $clients->page    // 1
echo $clients->pages   // 1
echo $clients->perPage // 30
echo $clients->total   // 6
```

To make a paginated call, first create a `PaginateBuilder` that can be passed into the `list` method.

```php
use amcintosh\FreshBooks\Builder\PaginateBuilder;

$paginator = new PaginateBuilder(2, 4);

$clients = $freshBooksClient->clients()->list($accountId, builders: [$paginator]);

echo $clients->page    // 2
echo $clients->pages   // 2
echo $clients->perPage // 4
echo $clients->total   // 6
```

`PaginateBuilder` has chainable methods `page` and `perPage` to set the values.

```php
$paginator = new PaginateBuilder(1, 3);
echo $paginator->page;    // 1
echo $paginator->perPage; // 3

$paginator->page(2)->perPage(4);
echo $paginator->page;    // 2
echo $paginator->perPage; // 4
```

##### Filters

TODO:

##### Includes

TODO:

##### Sorting

TODO:

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
