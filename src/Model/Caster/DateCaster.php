<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use DateTime;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class DateCaster implements Caster
{
    private const FORMAT = 'Y-m-d';

    /**
     * @param string|mixed $value
     *
     * @return DateTime
     */
    public function cast(mixed $value): DateTime
    {
        return new DateTime($value, new DateTimeZone('UTC'));
    }
}
