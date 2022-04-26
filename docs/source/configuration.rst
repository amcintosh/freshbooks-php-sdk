
Configuring The API Client
==========================

You can create an instance of the API client in one of two ways:

* By providing your application's OAuth2 ``clientId`` and ``clientSecret`` and following through the auth flow, which
  when complete will return an access token.
* Or if you already have a valid access token, you can instantiate the client with that token, however token refresh
  flows will not function without the application id and secret.

.. code-block:: php
    use amcintosh\FreshBooks\FreshBooksClient;
    use amcintosh\FreshBooks\FreshBooksClientConfig;

    $conf = new FreshBooksClientConfig(
        clientSecret: 'your secret',
        redirectUri: 'https://some-redirect',
    );

    $freshBooksClient = new FreshBooksClient('your application id', $conf);

and then proceed with the auth flow (see below).

Or

.. code-block:: php
    use amcintosh\FreshBooks\FreshBooksClient;
    use amcintosh\FreshBooks\FreshBooksClientConfig;

    $conf = new FreshBooksClientConfig(
        accessToken: 'a valid token',
    );

    $freshBooksClient = new FreshBooksClient('your application id', $conf);
