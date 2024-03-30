<?php

/*
    This is a simple server endpoint used to demonstrate the webhook
    flow seen in `./webhook_flow.php`.
    Please look over the instructions in the examples `README.md` for setup.
*/

require __DIR__ . '/../vendor/autoload.php';

if (array_key_exists('HTTP_X_FRESHBOOKS_HMAC_SHA256', $_SERVER)) {
    $data = array(
        $_SERVER['HTTP_X_FRESHBOOKS_HMAC_SHA256'],
        json_encode($_POST)
    );

    $fp = fopen('./webhooks.csv', 'wb');
    fputcsv($fp, $data);
    fclose($fp);
}
