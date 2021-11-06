<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Client;

/**
 * A client in the new FreshBooks is a resource representing an entity you send invoices to.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/clients
 */
class ClientList extends DataTransferObject
{
    public const RESPONSE_FIELD = 'clients';

    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;

    #[CastWith(ArrayCaster::class, itemType: Client::class)]
    public array $clients;
}
