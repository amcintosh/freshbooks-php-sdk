Current User
============

FreshBooks users are uniquely identified by their email across our entire product. One user may act on several
Businesses in different ways, and our Identity model is how we keep track of it. Each unique user has an Identity,
and each Identity has Business Memberships which define the permissions they have.

See `FreshBooks API - Business, Roles, and Identity <https://www.freshbooks.com/api/me_endpoint>`_ and
`FreshBooks API - The Identity Model <https://www.freshbooks.com/api/identity_model>`_.

The current user can be accessed by:

.. code-block:: php
    $identity = $freshBooksClient->currentUser()
    echo $identity.email // prints the current user's email

    // Print name and role of each business the user is a member of
    foreach ($identity.businessMemberships as $businessMembership) {
        echo $businessMembership->business.name
        echo $businessMembership->role; // eg. owner
    }
