
Pagination, Filters, Includes, and Sorting
==========================================

:doc:`list<get-list>` calls take a list of builder objects that can be used to paginate, filter, sort, and include
optional data in the response. See `FreshBooks API - Parameters <https://www.freshbooks.com/api/parameters>`_
documentation.

Pagination
----------

Pagination results are included in :doc:`list<get-list>` responses:

.. code-block:: php
    $clients = $freshBooksClient->clients()->list($accountId);

    echo $clients->page    // 1
    echo $clients->pages   // 1
    echo $clients->perPage // 30
    echo $clients->total   // 6

To make a paginated call, first create a ``PaginateBuilder`` that can be passed into the ``list`` method.

.. code-block:: php
    use amcintosh\FreshBooks\Builder\PaginateBuilder;

    $paginator = new PaginateBuilder(2, 4);

    $clients = $freshBooksClient->clients()->list($accountId, builders: [$paginator]);

    echo $clients->page    // 2
    echo $clients->pages   // 2
    echo $clients->perPage // 4
    echo $clients->total   // 6

``PaginateBuilder`` has chainable methods ``page`` and ``perPage`` to set the values.

.. code-block:: php
    $paginator = new PaginateBuilder(1, 3);
    echo $paginator->page;    // 1
    echo $paginator->perPage; // 3

    $paginator->page(2)->perPage(4);
    echo $paginator->page;    // 2
    echo $paginator->perPage; // 4

Filters
-------

To filter which results are return by :doc:`list<get-list>` method calls, construct a ``FilterBuilder`` and pass that
in the list of builders to the ``list`` method.

.. code-block:: php
    use amcintosh\FreshBooks\Builder\FilterBuilder;

    $filters = new FilterBuilder();
    $filters->equals('userid', 123);

    $clients = $freshBooksClient->clients()->list($accountId, builders: [$filters]);

Filters can be built with the methods: ``equals``, ``inList``, ``like``, ``between``, ``boolean``, and ``datetime``
which can be chained together.

.. code-block:: php
    $filters = new FilterBuilder();
    $filters->like('email_like', '@freshbooks.com');
    // Creates `&search[email_like]=@freshbooks.com`

    $filters = new FilterBuilder();
    $filters->inList('clientids', [123, 456])->boolean('active', false);
    // Creates `&search[clientids][]=123&search[clientids][]=456&active=false`

    $filters = new FilterBuilder();
    $filters->between('amount', 1, 10);
    // Creates `&search[amount_min]=1&search[amount_max]=10`

    $filters = new FilterBuilder();
    $filters->between("start_date", min: new DateTime('2020-10-17'))
    // Creates `&search[start_date]=2020-10-17`

Includes
--------

To include additional relationships, sub-resources, or data in a response an ``IncludesBuilder``
can be constructed.

.. code-block:: php
    use amcintosh\FreshBooks\Builder\IncludesBuilder;

    $includes = new IncludesBuilder();
    $includes->include("outstanding_balance");

Which can then be passed into ``list`` or ``get`` calls:

.. code-block:: php
    $clients = $freshBooksClient->clients()->list($accountId, builders: [$includes]);
    echo $clients->clients[0]->outstanding_balance->amount; // '100.00'
    echo $clients->clients[0]->outstanding_balance->code; // 'USD'

    $client = $freshBooksClient->clients()->get($accountId, $clientId, $includes);
    echo $client->outstanding_balance->amount; // '100.00'
    echo $client->outstanding_balance->code; // 'USD'

Includes can also be passed into :doc:`create<create-update-delete>` and :doc:`update<create-update-delete>` calls t
o include the data in the response of the updated resource:

.. code-block:: php
    $clientData = array(
        'email' => 'john.doe@abcorp.com'
    );

    $newClient = $freshBooksClient->clients()->create($accountId, data: $clientData, includes: $includes);

    echo $client->outstanding_balance->amount; // null, new client has no balance

Sorting
-------

To sort the results of a list call by supported fields (see the documentation for that resource) a
``SortBuilder` can be used.

.. code-block:: php
    use amcintosh\FreshBooks\Builder\SortBuilder;

    $sort = new SortBuilder();
    $sort->ascending("invoice_date");

    $invoices = $freshBooksClient->invoices()->list($accountId, builders: [$sort]);

to sort by the invoice date in ascending order, or:

.. code-block:: php
    use amcintosh\FreshBooks\Builder\SortBuilder;

    $sort = new SortBuilder();
    $sort->descending("invoice_date");

    $invoices = $freshBooksClient->invoices()->list($accountId, builders: [$sort]);

for descending order.
