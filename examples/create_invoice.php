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

$accountId = "6VApk";
$accessToken = 'd9e165343d6ef400e08ccb6816f619902d0b52ad43b9aaed5c52c64610fcc9c2';

$conf = new FreshBooksClientConfig(accessToken: $accessToken);
$freshBooksClient = new FreshBooksClient('12345', $conf);

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
echo 'Created client "' . $client->id . "\n";

// Create the invoice
$line1 = new LineItem();
$line1->name = 'Fancy Dishes';
$line1->description = "They're pretty swanky";
$line1->quantity = 6;
$line1->unitCost = new Money(Decimal::create('24.99'), 'CAD');

$line2 = new LineItem();
$line2->name = 'Regular Glasses';
$line2->description = 'They look "just ok"';
$line2->quantity = 8;
$line2->unitCost = new Money(Decimal::create('5.95'), 'CAD');

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
echo 'Created invoice "' . $invoice->id . "\n";
echo 'Invoice total is ' . $invoice->amount->amount . ' ' . $invoice->amount->code . "\n";
