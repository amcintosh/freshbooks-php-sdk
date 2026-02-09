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
        $parsedDate = DateTimeImmutable::createFromFormat(
            Util::ACCOUNTING_FORMAT,
            $value,
            new DateTimeZone(Util::ACCOUNTING_TIMEZONE)
        );
        return $parsedDate->setTimeZone(new DateTimeZone('UTC'));
    }

    /**
     * Helper function to convert content from DataModel objects or arrays of DataModel objects.
     *
     * @param array $data The data array to modify.
     * @param string $key The key in the data array to set.
     * @param mixed $value The value to convert, can be a DataModel, an array of DataModel, or any other type.
     * @return void
     */
    public static function convertContent(array &$data, string $key, mixed $value): void
    {
        if ($value === null) {
            return;
        }
        if (is_array($value)) {
            $convertedItems = [];
            foreach ($value as $item) {
                if ($item instanceof DataModel) {
                    $convertedItems[] = $item->getContent();
                } else {
                    $convertedItems[] = $item;
                }
            }
            $data[$key] = $convertedItems;
        } elseif ($value instanceof DataModel) {
            $data[$key] = $value->getContent();
        } else {
            $data[$key] = $value;
        }
    }
}
