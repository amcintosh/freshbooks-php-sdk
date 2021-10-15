<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

class Client
{

    private ClientConfig $config;

    public function __construct(string $clientId, $config = null)
    {
        $this->config = $config;
        $this->config->clientId = $clientId;
    }

    public function printConfig()
    {
        var_dump($this->config->clientId);
        var_dump($this->config->clientSecret);
        var_dump($this->config->getUserAgent());
    }
}
