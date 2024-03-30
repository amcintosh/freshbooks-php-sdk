# Examples and Sample Code

This directory contains examples and sample code for using FreshBooks with this SDK.

If you checkout the project, these files should be runnable locally after installing.

```shell
composer install
php ./examples/create_invoice.php
```

Be sure to update the example files with your own credentials in place of things like `<your client_id>`,
`<your account id>`, and `<your access token>`.

## Webhooks Example

In order to demonstrate the use of webhooks, an active server is required to receive the webhook
callbacks from FreshBooks. This server must also be accessible to the open internet.

To facilitate this, the example provides some simple server code in `webhook_server.php` that will
receive the webhook and store the params and verifier signature into a csv.

To make this accessible to FreshBooks, we suggest a tool like [ngrok](https://ngrok.com/).

The example code to register a webhook for client creation events, verify the webhook, and then
create a client and receive the webhook callback is in `webhook_flow.php`.

Thus, to setup this flow:

1. Install ngrok
2. Update the `webhook_flow.php` example with your own credentials for `$fbClientId`,
   `$accountId`, and `$accessToken`.
3. Start ngrok:

    ```shell
    ngrok http 8000
    ```

4. Copy the ngrok "Forwarding" url (eg. `https://6e33-23-233.ngrok-free.app`) and set it as the `$uri`
   variable in `webhook_flow.php`.
5. Start the webserver:

    ```shell
    php -S 127.0.0.1:8000 webhook_server.php
    ```

6. Run the sample code: php ./webhook_flow.php
