<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use DateTimeImmutable;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class ISODateTimeImmutableCaster implements Caster
{
    private const FORMAT = 'Y-m-d\TH:i:s\Z';
    private const FORMAT_NO_DESIGNATOR = 'Y-m-d\TH:i:s';

    public function __construct(
        private array $types,
        private bool $includeTimeZoneDesignator = true
    ) {
    }

    /**
     * @param string|mixed $value
     *
     * @return DateTimeImmutable
     */
    public function cast(mixed $value): DateTimeImmutable
    {
        if (!$this->includeTimeZoneDesignator) {
            return DateTimeImmutable::createFromFormat($this::FORMAT_NO_DESIGNATOR, $value, new DateTimeZone('UTC'));
        }
        return DateTimeImmutable::createFromFormat($this::FORMAT, $value, new DateTimeZone('UTC'));
    }
}
