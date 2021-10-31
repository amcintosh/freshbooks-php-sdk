<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use DateTimeImmutable;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class AccountingDateTimeImmutableCaster implements Caster
{
    private const TIMEZONE = 'US/Eastern';
    private const FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string|mixed $value
     *
     * @return DateTimeImmutable
     */
    public function cast(mixed $value): DateTimeImmutable
    {

        $parsedDate = DateTimeImmutable::createFromFormat($this::FORMAT, $value, new DateTimeZone($this::TIMEZONE));
        return $parsedDate->setTimeZone(new DateTimeZone('UTC'));
    }
}
