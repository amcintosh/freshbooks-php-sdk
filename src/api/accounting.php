<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\api;

use Http\Client\Common\HttpMethodsClient;

class Accounting
{

    public function __construct(HttpMethodsClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get(string $accountId, int $id)
    {
        $url = '/accounting/account/' . $accountId . '/users/clients/' . $id;
        var_dump($url);
        $response = $this->httpClient->get($url);
        return $response->getBody()->getContents();
        #return json_decode($response->getBody()->getContents(), true);
    }
}
