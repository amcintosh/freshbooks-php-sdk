<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Psr\Http\Message\StreamInterface;

/**
 * A file that has been uploaded to FreshBooks.
 *
 * @package amcintosh\FreshBooks\Model
 */
class FileUpload
{
    /**
     * @var string The JWT used to fetch the file from FreshBooks.
     */
    public ?string $jwt;

    /**
     * @var string The name of the file uploaded to FreshBooks.
     *
     * This is returned from the API in the `X-filename` header.
     */
    public ?string $fileName;

    /**
     * @var string The media type (eg. `image/png`) of the file uploaded to FreshBooks.
     */
    public ?string $mediaType;

    /**
     * @var string The PSR StreamInterface steam of data from the request body.
     */
    public ?StreamInterface $responseBody;

    /**
     * @var string A fully qualified path the the file from FreshBooks.
     */
    public ?string $link;

    public function __construct(?string $fileName, ?string $mediaType, ?StreamInterface $responseBody)
    {
        $this->fileName = $fileName;
        $this->mediaType = $mediaType;
        $this->responseBody = $responseBody;
    }
}
