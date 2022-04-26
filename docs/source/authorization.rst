
Authorization Flow
==================

*This is a brief summary of the OAuth2 authorization flow and the methods in the FreshBooks API Client
around them. See the `FreshBooks API - Authentication <https://www.freshbooks.com/api/authentication>`_
documentation.*

First, instantiate your Client with ``clientId``, ``clientSecret``, and ``redirectUri`` as above.

To get an access token, the user must first authorize your application. This can be done by sending the user to
the FreshBooks authorization page. Once the user has clicked accept there, they will be redirected to your
``redirectUri`` with an access grant code. The authorization URL can be obtained by calling
``$freshBooksClient->getAuthRequestUri()``. This method also accepts a list of scopes that you wish the user to
authorize your application for.

.. code-block:: php
    $authUrl = $freshBooksClient->getAuthRequestUri(['user:profile:read', 'user:clients:read']);

Once the user has been redirected to your ``redirectUri`` and you have obtained the access grant code, you can exchange
that code for a valid access token.

.. code-block:: php
    $authResults = $freshBooksClient->getAccessToken($accessGrantCode);

This call both sets the ``accessToken``, ``refreshToken``, and ``tokenExpiresAt`` fields on you Client's
FreshBooksClientConfig instance and returns those values.

.. code-block:: php
    echo $authResults->accessToken;  // Your token
    echo $authResults->refreshToken; // Your refresh token
    echo $authResults->createdAt;    // When the token was created (as a DateTime)
    echo $authResults->expiresIn;    // How long the token is valid for (in seconds)
    echo $authResults->getExpiresAt; // When the token expires (as a DateTime)

    echo $freshBooksClient->getConfig()->accessToken;    // Your token
    echo $freshBooksClient->getConfig()->refreshToken;   // Your refresh token
    echo $freshBooksClient->getConfig()->tokenExpiresAt; // When the token expires (as a DateTime)

When the token expires, it can be refreshed with the ``refreshToken`` value in the FreshBooksClient:

.. code-block:: php
    $authResults = $freshBooksClient->refreshAccessToken();
    echo $authResults->accessToken;  // Your new token

or you can pass the refresh token yourself:

.. code-block:: php
    $authResults = $freshBooksClient->refreshAccessToken($storedRefreshToken);
    echo $authResults->accessToken;  // Your new token
