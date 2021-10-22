<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Exception;

final class FreshBooksException extends \Exception
{
    public function __construct(
        string $message,
        int $statusCode,
        Throwable $previous = null,
        string $rawResponse = null,
        int $errorCode = null
    ) {
        parent::__construct($message, $statusCode, $previous);

        $this->rawResponse = $rawResponse;
        $this->errorCode = $errorCode;
    }

    public function getRawResponse(): ?string
    {
        return $this->rawResponse;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }
}
