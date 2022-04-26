Making API Calls
================

Each resource in the client has provides calls for ``get``, ``list``, ``create``, ``update`` and ``delete`` calls. Please note
that some API resources are scoped to a FreshBooks ``accountId`` while others are scoped to a ``businessId``. In general
these fall along the lines of accounting resources vs projects/time tracking resources, but that is not precise.

.. code-block:: php
    $client = $freshBooksClient->clients()->get($accountId, $clientId);
    $project = $freshBooksClient->projects()->get($businessId, $projectId);

Usage
-----

.. toctree::
    :maxdepth: 2

    current-user
    decimals
    get-list
    create-update-delete
    errors
    builders

.. |phpdoc| replace:: phpDocumentor