<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Client;

/**
 * Results of clients list call containing list of clients and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/clients
 */
class ClientList extends AccountingList
{
    public array $clients;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->clients = $this->constructList($data['clients'], Client::class);
    }
}
