Error Handling
==============

Calls made to the FreshBooks API with a non-2xx response are wrapped in a ``FreshBooksException``.
This exception class contains the error message, HTTP response code, FreshBooks-specific error number if one exists,
and the HTTP response body.

Example:

.. code-block:: php
    use amcintosh\FreshBooks\Exception\FreshBooksException;

    try {
        $client = $freshBooksClient->clients()->get($accountId, 134);
    } catch (FreshBooksException $e) {
        echo $e->getMessage();     // 'Client not found'
        echo $e->getCode();        // 404
        echo $e->getErrorCode();   // 1012
        echo $e->getRawResponse(); // '{"response": {"errors": [{"errno": 1012,
                                // "field": "userid", "message": "Client not found.",
                                // "object": "client", "value": "134"}]}}'
    }

Not all resources have full CRUD methods available. For example expense categories have ``list`` and ``get``
calls, but are not deletable. If you attempt to call a method that does not exist, the SDK will raise a
``FreshBooksNotImplementedError`` exception, but this is not something you will likely have to account
for outside of development.
