<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Exception;

/**
 * Exception thrown when the SDK has not been configured properly.
 *
 * @package amcintosh\FreshBooks\Exception
 */
final class FreshBooksClientConfigException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}
