File Uploads
============

Some FreshBooks resource can include images and attachments. For example, invoices can have a company
logo or banner image as part of the invoice presentation object as well as images or pdfs attachments.
Expenses can also include copies or photos of receipts as attachments.

All images and attachments first need to be uploaded to FreshBooks via the ``images`` or ``attachments``
endpoints.

These will then return a path to your file with a JWT. This path will can then be passed as part of the
data in a subsequent call.

See FreshBooks' `invoice attachment <https://www.freshbooks.com/api/invoice_presentation_attachments>`_
and `expense attachment <https://www.freshbooks.com/api/https://www.freshbooks.com/api/expense-attachments>`_
documentation for more information.

Invoice Images and Attachments
------------------------------

See `FreshBooks' API Documentation <https://www.freshbooks.com/api/invoice_presentation_attachments>`_.

The ``upload()`` function takes a `PHP resource <https://www.php.net/manual/en/language.types.resource.php>`_.
Logo's and banners are added to the invoice presentation objec.t To include an uploaded attachment on
an invoice, the invoice request must include an attachments object.

.. code-block:: php
    $logo = $freshBooksClient->images()->upload($accountId, fopen('./sample_logo.png', 'r'));
    $attachment = $freshBooksClient->attachments()->upload($accountId, fopen('./sample_attachment.pdf', 'r'));

    $presentation = [
        'image_logo_src' => "/uploads/images/{$logo->jwt}",
        'theme_primary_color' => '#1fab13',
        'theme_layout' => 'simple'
    ];

    $invoiceData = [
        'customerid' => $clientId,
        'attachments' => [
            [
                'jwt' => $attachment->jwt,
                'media_type' => $attachment->mediaType
            ]
        ],
        'presentation' => presentation
    ];

    $invoice = $freshBooksClient->invoices()->create($accountId, $invoiceData);

Expense Receipts
----------------

See `FreshBooks' API Documentation <https://www.freshbooks.com/api/expense-attachments>`_.
