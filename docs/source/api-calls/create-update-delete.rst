Create, Update, and Delete
==========================

API calls to create and update take either a ``DataModel`` object, or an array of the resource data. A successful call
will return a ``DataModel`` object as if a :doc:`get<get-list>` call.

*Note*: When using the array of data, you need to specify the field as it exists in the FreshBooks API. There
are API fields that are translated to more intuitive names in the data models. For example ``fname`` = ``firstName``,
or ``bus_phone`` = ``businessPhone``.

Create
------

.. code-block:: php
    $clientData = new Client();
    $clientData->organization = 'FreshBooks';
    $clientData->firstName = 'Gordon';
    $clientData->businessPhone = '416-444-4445';

    $newClient = $freshBooksClient->clients()->create($accountId, model: $clientData);

    echo $newClient->organization;  // 'FreshBooks'
    echo $newClient->firstName;     // 'Gordon'
    echo $newClient->businessPhone; // '416-444-4445'

or

.. code-block:: php
    $clientData = array(
        'organization' => 'FreshBooks',
        'fname' => 'Gordon',
        'bus_phone' => '416-444-4445'
    );

    $newClient = $freshBooksClient->clients()->create($accountId, data: $clientData);

    echo $newClient->organization;  // 'FreshBooks'
    echo $newClient->firstName;     // 'Gordon'
    echo $newClient->businessPhone; // '416-444-4445'

Update
------

.. code-block:: php
    $clientData->organization = 'New Org';
    $clientData->firstName = 'Gord';

    $newClient = $freshBooksClient->clients()->update($accountId, $clientData->id, model: $clientData);

    echo $newClient->organization; // 'New Org'
    echo $newClient->firstName;    // 'Gord'

or

.. code-block:: php
    $clientData = array(
        'organization' => 'Really New Org',
        'fname' => 'Gord',
    );

    $newClient = $freshBooksClient->clients()->update($accountId, $clientId, data: $clientData);

    echo $newClient->organization; // 'Really New Org'
    echo $newClient->firstName;    // 'Gord'

Delete
------

.. code-block:: php
    $client = $freshBooksClient->clients()->delete($accountId, $clientId);

    echo $client->visState; // '1'
    echo $client->visState == VisState::ACTIVE ? 'Is Active' : 'Not Active'; // 'Not Active'
