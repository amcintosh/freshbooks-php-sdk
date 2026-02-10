Get and List
============

Get
---

Get calls return a single `DataModel` class with data accessible via properties.

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

List calls return a class containing containing an array of the resource's `DataModel` class.

.. code-block:: php
    $clients = $freshBooksClient->clients()->list($accountId);

    echo $clients->clients[0]->organization; // 'FreshBooks'

    foreach ($clients->clients as $client) {
        echo $client->organization;
    }
