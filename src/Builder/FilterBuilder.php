<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Builder;

use DateTime;
use amcintosh\FreshBooks\Builder\BuilderInterface;

/**
 * Builder for making filtered list queries.
 *
 * @package amcintosh\FreshBooks\Builder
 * @link https://www.freshbooks.com/api/parameters
 */
class FilterBuilder implements BuilderInterface
{
    public array $filters;

    /**
     * Builder for making filtered list queries.
     *
     * @return void
     */
    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * Filters results where the provided field is between two values.
     *
     * In general 'between' filters end in a `_min` or `_max` (as in `amount_min` or `amount_max`)
     * or `_date` (as in `start_date`, `end_date`). If the provided field does not end in
     * `_min`/`_max` or `_date`, then the appropriate `_min`/`_max` will be appended.
     *
     *  For date fields, you can pass the iso format `2020-10-17` or a `datetime` or `date` object, which
     *  will be converted to the proper string format.
     *
     *  Examples:
     *
     *  - `$filter->between('amount', 1, 10)` will yield filters `&search[amount_min]=1&search[amount_max]=10`
     *  - `$filter->between('amount_min', min: 1)` will yield filter `&search[amount_min]=1`
     *  - `$filter->between('amount_max', max: 10)` will yield filter `&search[amount_max]=10`
     *  - `$filter->between('start_date', min: '2020-10-17')` will yield filter `&search[start_date]=2020-10-17`
     *  - `$filter->between('start_date', min: new DateTime('2020-10-17'))` will yield filter
     *    `&search[start_date]=2020-10-17`
     *
     * @param  string $key The API response field to filter on
     * @param  mixed $min (Optional) The value the field should be greater than (or equal to)
     * @param  mixed $max (Optional) The value the field should be less than (or equal to)
     * @return self The FilterBuilder instance
     */
    public function between(string $key, mixed $min = null, mixed $max = null): self
    {
        if (!is_null($min)) {
            $minKey = $this->convertBetweenKey($key, "_min");
            $minValue = $this->convertBetweenValue($min);
            $this->filters[] = ['between', $minKey, $minValue];
        }
        if (!is_null($max)) {
            $maxKey = $this->convertBetweenKey($key, "_max");
            $maxValue = $this->convertBetweenValue($max);
            $this->filters[] = ['between', $maxKey, $maxValue];
        }
        return $this;
    }

    private function convertBetweenKey(string $key, string $suffix): string
    {
        if (str_ends_with($key, '_min') || str_ends_with($key, '_max') || str_ends_with($key, '_date')) {
            return $key;
        }
        return $key . $suffix;
    }

    private function convertBetweenValue(mixed $value): string
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d');
        }
        return strval($value);
    }

    /**
     * Filters results where the field is equal to true or false.
     *
     * Example:
     *    `$filter->boolean('active', false)` will yield the filter `&active=false`
     *
     * @param  string $key The API response field to filter on
     * @param  bool $value true or false
     * @return self The FilterBuilder instance
     */
    public function boolean(string $key, bool $value): self
    {
        $this->filters[] = ['bool', $key, $value ? 'true' : 'false'];
        return $this;
    }

    /**
     * Filters for entries that come before or after a particular time, as specified
     * by the field.
     *
     * Eg. "updated_since" on Time Entries will return time entries updated after the provided time.
     *
     * If a string is provided, it must be in ISO 8601 format (eg. '2010-10-17T05:45:53')
     *
     * Example:
     *    `$filter->datetime('updated_since', '2020-10-17T07:03:01')` will yield the filter
     *    `&updated_since=2020-10-17T07:03:01`
     *
     * Similarly:
     *   `$filter->datetime('updated_since', new DateTime('2020-10-17T07:03:01.012345Z', new DateTimeZone('UTC')))`
     *   will yield the same.
     *
     * @param  string $key The API response field to filter on
     * @param  mixed $value The DateTime object or ISO 8601 format string value
     * @return self The FilterBuilder instance
     */
    public function datetime(string $key, mixed $value): self
    {
        if ($value instanceof DateTime) {
            $this->filters[] = ['datetime', $key, $value->format('Y-m-d\TH:i:s')];
        } else {
            $this->filters[] = ['datetime', $key, $value];
        }
        return $this;
    }

    /**
     * Filters results where the field is equal to the provided value.
     *
     * Example:
     *    `$filter->equals('username', 'Bob')` will yield the filter `&search[username]=Bob`
     *
     * @param  string $key The API response field to filter on
     * @param  mixed $value The value the field should equal
     * @return self The FilterBuilder instance
     */
    public function equals(string $key, mixed $value): self
    {
        $this->filters[] = ['equals', $key, $value];
        return $this;
    }

    /**
     * Filters if the provided field matches a value in a list.
     *
     * Example:
     *    `$filter->equals('username', 'Bob')` will yield the filter `&search[username]=Bob`
     *
     * @param  string $key The API response field to filter on
     * @param  array $value The value the field should equal
     * @return self The FilterBuilder instance
     */
    public function inList(string $key, array $values): self
    {
        if (!str_ends_with($key, 's')) {
            $key .= 's';
        }
        $this->filters[] = ['in', $key, $values];
        return $this;
    }

    /**
     * Filters for a match contained within the field being searched. For example,
     * "leaf" will Like-match "aleaf" and "leafy", but not "leav", and "leafs" would
     * not Like-match "leaf".
     *
     * @param  string $key The API response field to filter on
     * @param  mixed $value The value the field should contain
     * @return self The FilterBuilder instance
     */
    public function like(string $key, mixed $value): self
    {
        $this->filters[] = ['like', $key, $value];
        return $this;
    }

    /**
     * Builds the query string parameters from the Builder.
     *
     * @param  string $resourceName The type of resource to generate the query string for.
     *               Eg. AccountingResource, ProjectsResource
     * @return string The built query string
     */
    public function build(string $resourceName = null): string
    {
        $isAccountingLike = false;
        if (is_null($resourceName) || in_array($resourceName, ['AccountingResource', 'EventsResource'], true)) {
            $isAccountingLike = true;
        }
        $queryString = '';
        foreach ($this->filters as $filter) {
            $filterType = $filter[0];
            $key = $filter[1];
            $value = $filter[2];
            if (
                in_array($filterType, ['like', 'between'], true) ||
                ($isAccountingLike === true && $filterType === 'equals')
            ) {
                $queryString .= "&search[{$key}]={$value}";
            } elseif ($filterType === 'in') {
                foreach ($value as $subValue) {
                    $queryString .= "&search[{$key}][]={$subValue}";
                }
            } elseif (
                in_array($filterType, ['bool', 'datetime'], true) ||
                ($isAccountingLike === false && $filterType === 'equals')
            ) {
                $queryString .= "&{$key}={$value}";
            }
        }
        return $queryString;
    }
}
