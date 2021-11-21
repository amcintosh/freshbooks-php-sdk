<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use DateTimeImmutable;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class ISODateTimeImmutableCaster implements Caster
{
    private const FORMAT = 'Y-m-d\TH:i:s\Z';

    public function __construct(
        private array $types,
    ) {
    }

    /**
     * @param string|mixed $value
     *
     * @return DateTimeImmutable
     */
    public function cast(mixed $value): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat($this::FORMAT, $value, new DateTimeZone('UTC'));
    }
}
