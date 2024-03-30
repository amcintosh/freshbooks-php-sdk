<?php

/*
    This is an example to show how to register for webhooks, verify them,
    and respond to a webhook. This requires an active server, so please
    look over the instructions in the examples `README.md` for setup.
*/

require __DIR__ . '/../vendor/autoload.php';

use amcintosh\FreshBooks\Model\Callback;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\FreshBooksClient;
use amcintosh\FreshBooks\FreshBooksClientConfig;
use amcintosh\FreshBooks\Builder\PaginateBuilder;
use Spryker\DecimalObject\Decimal;

$fbClientId = '<your client_id>';
$accountId = '<your account_id>';
$accessToken = '<your access token>';
$uri = '<your ngrok uri>';

$conf = new FreshBooksClientConfig(accessToken: $accessToken);
$freshBooksClient = new FreshBooksClient($fbClientId, $conf);

function getWebhookResponse()
{
    $fp = fopen('./webhooks.csv', 'r');
    $data = fgetcsv($fp, 1000, ",");
    fclose($fp);
    return $data;
}

function verifyWebhookData($verifier, $signature, $data)
{
    $data = json_encode($data);
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

    return $calculated_signature === $signature;
}

// Create a webhook callback
$createData = new Callback();
$createData->event = 'client.create';
$createData->uri = $uri;

echo "Creating webhook...\n";
try {
    $callback = $freshBooksClient->callbacks()->create($accountId, model: $createData);
} catch (\amcintosh\FreshBooks\Exception\FreshBooksException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

sleep(5);

$webhookData = getWebhookResponse();
$webhookSignature = $webhookData[0];
$webhookParams = json_decode($webhookData[1], true);
$verifier = $webhookParams['verifier'];
$webhookId = $webhookParams['object_id'];

echo "Recieved verification webhook for webhook_id {$webhookId} with verifier {$verifier}\n";

echo "Sending webhook verification...\n\n";
try {
    $freshBooksClient->callbacks()->verify($accountId, $webhookId, $verifier);
} catch (\amcintosh\FreshBooks\Exception\FreshBooksException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

echo "Creating client to test webhook...\n";
try {
    $client = $freshBooksClient->clients()->create($accountId, data: array('organization' => 'PHP Test Client'));
} catch (\amcintosh\FreshBooks\Exception\FreshBooksException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}
echo 'Created client "' . $client->id . "\"\n";

sleep(5);

$webhookData = getWebhookResponse();
$webhookSignature = $webhookData[0];
$webhookParams = json_decode($webhookData[1], true);

echo "Recieved webhook {$webhookParams['name']} with id {$webhookParams['object_id']} and signature {$webhookSignature}\n";
if (verifyWebhookData($verifier, $webhookSignature, $webhookParams)) {
    echo "\nData validated by signature!\n";
} else {
    echo "\nSignature validation failed\n";
}
