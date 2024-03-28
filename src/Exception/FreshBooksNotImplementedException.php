<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Exception;

/**
 * Exception thrown when attempting to make an API call that is not supported.
 *
 * @package amcintosh\FreshBooks\Exception
 */
final class FreshBooksNotImplementedException extends \Exception
{
    public function __construct(
        string $resourceName,
        string $methodName
    ) {
        $message = "The method '" . $methodName . "' does not exist for " . $resourceName;
        parent::__construct($message, 0, null);
    }
}
