<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use amcintosh\FreshBooks\resources\AccountingResource;
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

class Client
{

    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactoryInterface;
    private StreamFactoryInterface $streamFactoryInterface;

    private ClientConfig $config;

    public function __construct(string $clientId, $config = null)
    {
        $this->config = $config;
        $this->config->clientId = $clientId;
        $this->httpClient = $this->createHttpClient();
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config->accessToken,
            'User-Agent' => $this->config->getUserAgent(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    private function createHttpClient(): HttpMethodsClient
    {
        $plugins = array(
            new BaseUriPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri('https://api.freshbooks.com')),
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
     * @return array
     */
    public function clients(): AccountingResource
    {
        return new AccountingResource($this->httpClient);
    }
}
