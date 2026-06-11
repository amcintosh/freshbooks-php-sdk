<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\DataModel;

/**
 * FreshBooks data utils.
 *
 * @package amcintosh\FreshBooks
 */
class Util
{
    private const ACCOUNTING_TIMEZONE = 'US/Eastern';
    private const ACCOUNTING_FORMAT = 'Y-m-d H:i:s';
    private const PROJECT_FORMAT = 'Y-m-d\TH:i:s\Z';
    private const PROJECT_FORMAT_NO_DESIGNATOR = 'Y-m-d\TH:i:s';
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * @param string|mixed $value
     *
     * @return DateTime
     */
    public static function getDate(mixed $value): DateTime
    {
        return new DateTime($value, new DateTimeZone('UTC'));
    }

    /**
     * Get a datetime zoned to UTC from an accounting endpoint date string.
     * The accounting service stores almost all dates in the US/Eastern timezone.
     *
     * @param string|mixed $value An accounting datetime string. eg. 2021-01-08 20:39:52
     * @param bool $isUtc Whether the input value is in UTC or in the accounting timezone.
     *
     * @return DateTimeImmutable
     */
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
     * Get a datetime zoned to UTC from an project-like endpoint date string.
     *
     * The project services store their dates in UTC, but depending on the resource do not
     * indicate that in the response. Eg. "2020-09-13T03:10:13" rather than "2020-09-13T03:10:13Z".
     *
     * @param string|mixed $value A project datetime string. eg. 2020-09-13T03:10:13
     *
     * @return DateTimeImmutable
     */
    public static function getProjectDateTimeFromNaiveUTC(string $value): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(Util::PROJECT_FORMAT_NO_DESIGNATOR, $value, new DateTimeZone('UTC'));
    }

    /**
     * Get a datetime zoned to UTC from an ISO date string. Eg. "2020-09-13T03:10:13Z"
     **
     * @param string|mixed $value A project datetime string. eg. "2020-09-13T03:10:13Z"
     * @return DateTimeImmutable
     */
    public static function getProjectDateTimeFromISO(string $value): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(Util::PROJECT_FORMAT, $value, new DateTimeZone('UTC'));
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
        } elseif ($value instanceof Decimal) {
            $data[$key] = $value->toString();
        } else {
            $data[$key] = $value;
        }
    }
}
