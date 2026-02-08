<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use DateTimeImmutable;
use DateTimeZone;

/**
 * FreshBooks data utils.
 *
 * @package amcintosh\FreshBooks
 */
class Util
{
    private const ACCOUNTING_TIMEZONE = 'US/Eastern';
    private const ACCOUNTING_FORMAT = 'Y-m-d H:i:s';

    public static function getAccountingDateTime(string $value, bool $isUtc = false): DateTimeImmutable
    {
        if ($isUtc) {
            return DateTimeImmutable::createFromFormat(Util::ACCOUNTING_FORMAT, $value, new DateTimeZone('UTC'));
        }
        $parsedDate = DateTimeImmutable::createFromFormat(Util::ACCOUNTING_FORMAT, $value, new DateTimeZone(Util::ACCOUNTING_TIMEZONE));
        return $parsedDate->setTimeZone(new DateTimeZone('UTC'));
    }
}
