<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Model\ClientList;
use amcintosh\FreshBooks\Model\Invoice;
use amcintosh\FreshBooks\Model\InvoiceList;
use amcintosh\FreshBooks\Model\Payment;
use amcintosh\FreshBooks\Model\PaymentList;
use amcintosh\FreshBooks\Model\Tax;
use amcintosh\FreshBooks\Model\TaxList;
use amcintosh\FreshBooks\Resource\AccountingResource;

class FreshBooksClient
{

    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactoryInterface;
    private StreamFactoryInterface $streamFactoryInterface;

    private FreshBooksClientConfig $config;

    public function __construct(string $clientId, $config)
    {
        $this->config = $config;
        $this->config->clientId = $clientId;
        $this->httpClient = $this->createHttpClient();
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config->accessToken,
            'User-Agent' => $this->config->getUserAgent(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function createHttpClient(): HttpMethodsClient
    {
        $plugins = array(
            new BaseUriPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($this->config->apiBaseUrl)),
            new HeaderDefaultsPlugin($this->getHeaders()),
        );

        $pluginClient = (new PluginClientFactory())->createClient(
            HttpClientDiscovery::find(),
            $plugins
        );

        return new HttpMethodsClient(
            $pluginClient,
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );
    }


    /**
     * FreshBooks clients resource with calls to get, list, create, update, delete
     *
     * @return AccountingResource
     */
    public function clients(): AccountingResource
    {
        return new AccountingResource($this->httpClient, 'users/clients', Client::class, ClientList::class);
    }

    /**
     * FreshBooks invoices resource with calls to get, list, create, update, delete
     *
     * @return AccountingResource
     */
    public function invoices(): AccountingResource
    {
        return new AccountingResource($this->httpClient, 'invoices/invoices', Invoice::class, InvoiceList::class);
    }

    /**
     * FreshBooks payments resource with calls to get, list, create, update, delete.
     *
     * @return AccountingResource
     */
    public function payments(): AccountingResource
    {
        return new AccountingResource($this->httpClient, 'payments/payments', Payment::class, PaymentList::class);
    }

    /**
     * FreshBooks taxes resource with calls to get, list, create, update, delete.
     *
     * @return AccountingResource
     */
    public function taxes(): AccountingResource
    {
        return new AccountingResource($this->httpClient, 'taxes/taxes', Tax::class, TaxList::class);
    }
}
