Webhook Callbacks
=================

The client supports registration and verification of FreshBooks' API Webhook Callbacks.
See `FreshBooks' documentation <https://www.freshbooks.com/api/webhooks>`_ for more information.

FreshBooks will send webhooks as a POST request to the registered URI with form data:

.. code-block:: http
    name=invoice.create&object_id=1234567&account_id=6BApk&business_id=6543&identity_id=1234user_id=1

Registration
------------

.. code-block:: php
    $clientData = array(
        'event' => 'invoice.create',
        'uri' => 'http://your_server.com/webhooks/ready'
    );

    $webhook = $freshBooksClient->callbacks()->create($accountId, data: $clientData);

    echo $webhook->callbackId;  // 2001
    echo $webhook->verified;     // false


Registration Verification
-------------------------

Registration of a webhook will cause FreshBooks to send a webhook to the specified URI with a
verification code. The webhook will not be active until you send that code back to FreshBooks.

.. code-block:: php
    $freshBooksClient->callbacks()->verify($accountId, $callbackId, $verificationCode);

If needed, you can ask FreshBooks to resend the verification code.

.. code-block:: php
    $freshBooksClient->callbacks()->resendVerification($accountId, $callbackId);

Hold on to the verification code for later use (see below).


Verifing Webhook Signature
--------------------------

Each Webhook sent by FreshBooks includes a header, ``X-FreshBooks-Hmac-SHA256``, with a base64-encoded
signature generated from a JSON string of the form data sent in the request and hashed with the token
originally sent in the webhook verification process as a secret.

From FreshBooks' documentation, this signature is gnerated in Python using:

.. code-block:: python
    import base64
    import hashlib
    import hmac
    import json

    msg = dict((k, str(v)) for k, v in message.items())
    dig = hmac.new(
        verifier.encode("utf-8"),
        msg=json.dumps(msg).encode("utf-8"),
        digestmod=hashlib.sha256
    ).digest()
    return base64.b64encode(dig).decode()

So to verify the signature in PHP:

.. code-block:: php
    $signature = $_SERVER['HTTP_X_FRESHBOOKS_HMAC_SHA256'];
    $data = json_encode($_POST);
    // Signature from FreshBooks calculated from Python json.dumps, which
    // produces {"key": "val", "key2": "val"}, but PHP json_encode
    // produces {"key":"value","key2","val"}
    $data = str_replace(":", ": ", $data);
    $data = str_replace(",", ", ", $data);

    $hash = hash_hmac(
        'sha256',
        iconv(mb_detect_encoding($data), "UTF-8", $data),
        iconv(mb_detect_encoding($verifier), "UTF-8", $verifier),
        true
    );
    $calculated_signature = base64_encode($hash);

    $isAuthentic = $calculated_signature === $signature;
