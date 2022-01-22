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
$fbClientSecret = '<your client secret>';
$redirectUri = '<your redirect uri>';

$conf = new FreshBooksClientConfig(
    clientSecret: $fbClientSecret,
    redirectUri: $redirectUri
);
$freshBooksClient = new FreshBooksClient($fbClientId, $conf);

$authorizationUrl = $freshBooksClient->getAuthRequestUri(
    ['user:profile:read', 'user:clients:read']
);
echo 'Go to this URL to authorize: ' . $authorizationUrl . "\n";

# The authorization code will be in the URL after the redirect
$authCode = readline('Enter the code you get after authorization: ');
$tokenResponse = $freshBooksClient->getAccessToken($authCode);
echo "\nThis is the access token the client is now configurated with: " . $tokenResponse->accessToken . "\n";
echo 'It is good until ' . $tokenResponse->getExpiresAt()->format('Y-m-d H:i:s') . "\n\n";

# Get the current user's identity
$identity = $freshBooksClient->currentUser();
$businesses = array();

# Display all of the businesses the user has access to
for ($i = 0; $i < count($identity->businessMemberships); $i++) {
    $business = $identity->businessMemberships[$i]->business;
    $businesses[] = [
        'name' => $business->name,
        'businessId' => $business->id,
        'accountId' => $business->accountId
    ];
    echo $i + 1 . ': ' . $business->name . "\n";
}
$businessIndex = readline('Which business do you want to use? ') - 1;

$business = $businesses[$businessIndex];
$businessId = $business['businessId'];  # Used for project-related calls
$accountId = $business['accountId'];  # Used for accounting-related calls

# Get a client for the business to show successful access
$client = $freshBooksClient->clients()->list($accountId)->clients[0];
echo '"' . $client->organization . '" is a client of ' . $business['name'] . "\n";
