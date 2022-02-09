<?php

/*
    This is an example where we create a new client and an invoice for them.
*/

require __DIR__ . '/../vendor/autoload.php';

use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Model\Invoice;
use amcintosh\FreshBooks\Model\LineItem;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\FreshBooksClient;
use amcintosh\FreshBooks\FreshBooksClientConfig;
use amcintosh\FreshBooks\Builder\PaginateBuilder;
use Spryker\DecimalObject\Decimal;

$fbClientId = '<your client_id>';
$accountId = '<your account_id>';
$accessToken = '<your access token>';

$conf = new FreshBooksClientConfig(accessToken: $accessToken);
$freshBooksClient = new FreshBooksClient($fbClientId, $conf);

// Create the client
$createData = new Client();
$createData->organization = 'PHP Test Client';

echo "Creating client...\n";
try {
    $client = $freshBooksClient->clients()->create($accountId, model: $createData);
} catch (\amcintosh\FreshBooks\Exception\FreshBooksException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}
echo 'Created client "' . $client->id . "\"\n";

// Create the invoice
$line1 = new LineItem();
$line1->name = 'Fancy Dishes';
$line1->description = "They're pretty swanky";
$line1->quantity = 6;
$line1->unitCost = new Money(Decimal::create('24.99'), 'CAD'); // Using a Decimal object

$line2 = new LineItem();
$line2->name = 'Regular Glasses';
$line2->description = 'They look "just ok"';
$line2->quantity = 8;
$line2->unitCost = new Money('5.95', 'CAD'); // Using a string, which is converted to a Decimal object

$model = new Invoice();
$model->lines = [$line1, $line2];
$model->clientId = $client->id;
$model->createDate = new \DateTime();

echo "Creating invoice...\n";
try {
    $invoice = $freshBooksClient->invoices()->create($accountId, model: $model);
} catch (\amcintosh\FreshBooks\Exception\FreshBooksException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}
echo 'Created invoice "' . $invoice->id . "\"\n";
echo 'Invoice total is ' . $invoice->amount->amount . ' ' . $invoice->amount->code . "\n";

// Invoices are created in draft status, so we need to mark it as sent
echo "Marking invoice as sent...\n";
$invoiceData = [
    'action_mark_as_sent' => true
];
$invoice = $freshBooksClient->invoices()->update($accountId, $invoice->id, data: $invoiceData);
