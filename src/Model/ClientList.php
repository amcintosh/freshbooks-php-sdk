<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingListLegacy;
use amcintosh\FreshBooks\Model\Client;

/**
 * Results of clients list call containing list of clients and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/clients
 */
class ClientList extends AccountingListLegacy
{
    public const RESPONSE_FIELD = 'clients';

    #[CastWith(ArrayCaster::class, itemType: Client::class)]
    public array $clients;
}
