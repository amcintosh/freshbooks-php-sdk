<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;

class Client extends DataTransferObject
{
    public int $id;

    public string $organization;

    #[MapFrom('fname')]
    public ?string $firstName;

    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public DateTimeImmutable $updated;
}
