Get and List
============

Get
---

API calls which return a single resource return a `DataTransferObject <https://github.com/spatie/data-transfer-object>`_
with the returned data accessible via properties.

.. code-block:: php
    $client = $freshBooksClient->clients()->get($accountId, $clientId);

    echo $client->organization; // 'FreshBooks'
    $client->only('organization')->toArray(); // ['organization' => 'FreshBooks'];

``visState`` numbers correspond with various states. See
`FreshBooks API - Active and Deleted Objects <https://www.freshbooks.com/api/active_deleted>`_ for details.

.. code-block:: php
    use amcintosh\FreshBooks\Model\VisState;

    echo $client->visState; // '0'
    echo $client->visState == VisState::ACTIVE ? 'Is Active' : 'Not Active'; // 'Is Active'

List
----

API calls which return a list of resources return a `DataTransferObject <https://github.com/spatie/data-transfer-object>`_
with an array of the resources.

.. code-block:: php
    $clients = $freshBooksClient->clients()->list($accountId);

    echo $clients->clients[0]->organization; // 'FreshBooks'

    foreach ($clients->clients as $client) {
        echo $client->organization;
    }
