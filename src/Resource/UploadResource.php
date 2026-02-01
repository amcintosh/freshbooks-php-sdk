<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\FileUpload;

class UploadResource extends BaseResource
{
    protected HttpClient $httpClient;
    protected string $uploadPath;
    protected string $resourceName;

    public function __construct(
        HttpClient $httpClient,
        string $uploadPath,
        string $resourceName,
    ) {
        $this->httpClient = $httpClient;
        $this->uploadPath = $uploadPath;
        $this->resourceName = $resourceName;
    }

    /**
     * The the url to the upload resource.
     *
     * @param  string $accountId
     * @param  int $resourceId
     * @return string
     */
    protected function getUrl(?string $accountId = null, ?string $jwt = null): string
    {
        if (!is_null($accountId)) {
            return "/uploads/account/{$accountId}/{$this->uploadPath}";
        }
        return "/uploads/{$this->uploadPath}/{$jwt}";
    }

    /**
     * Create a FreshBooksException from the json response from the uploads endpoint.
     *
     * @param  int $statusCode HTTP status code
     * @param  string $contents The response contents
     * @return void
     */
    protected function handleError(int $statusCode, string $contents): void
    {
        try {
            $responseData = json_decode($contents, true);
        } catch (JSONDecodeError $e) {
            throw new FreshBooksException('Failed to parse response', $statusCode, $e, $contents);
        }
        $message = $responseData['error'] ?? "Unknown error";
        throw new FreshBooksException($message, $statusCode, null, $contents, null, null);
    }

    /**
     * Make a request against the uploads resource. Returns an object containing a
     * Psr\Http\Message\StreamInterface for flexibility.
     * Throws a FreshBooksException if the response is not a 200.
     *
     * @param  string $url
     * @return FileUpload
     */
    private function makeGetFileRequest(string $url): FileUpload
    {
        $response = $this->httpClient->send(self::GET, $url);
        $responseBody = $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            $this->handleError($statusCode, $responseBody->getContents());
        }
        $fileName = $response->getHeader('X-filename');
        if (!is_null($fileName) && count($fileName) > 0) {
            $fileName = $fileName[0];
        }
        $mediaType = $response->getHeader('Content-Type');
        if (!is_null($mediaType) && count($mediaType) > 0) {
            $mediaType = $mediaType[0];
        }

        return new FileUpload($fileName, $mediaType, $responseBody);
    }

    /**
     * Make creates a POST request to upload a file to FreshBooks.
     * Throws a FreshBooksException if the response is not a 200.
     *
     * @param  string $url
     * @return FileUpload
     */
    private function makeUploadRequest(string $url, $file): FileUpload
    {
        // Thank you https://dev.to/timoschinkel/sending-multipart-data-with-psr-18-2lb5
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $builder = new MultipartStreamBuilder($streamFactory);
        $builder->addResource('content', $file);

        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $requestFactory
            ->createRequest('POST', $url)
            ->withHeader('Content-Type', 'multipart/form-data; boundary="' . $builder->getBoundary() . '"')
            ->withBody($builder->build());

        $response = $this->httpClient->sendRequest($request);

        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody();
        if ($statusCode >= 400) {
            $this->handleError($statusCode, $responseBody->getContents());
        }
        try {
            $contents = $responseBody->getContents();
            $responseData = json_decode($contents, true);
        } catch (JSONDecodeError $e) {
            throw new FreshBooksException('Failed to parse response', $statusCode, $e, $contents);
        }
        if (is_null($responseData) || !array_key_exists($this->resourceName, $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }
        $link = $responseData['link'] ?? null;
        $responseData = $responseData[$this->resourceName];
        $fileData = new FileUpload($responseData['filename'], $responseData['media_type'], null);
        $fileData->link = $link;
        $fileData->jwt = $responseData['jwt'];
        return $fileData;
    }

    /**
     * Get an uploaded file.
     *
     * @param  string $jwt JWT provided by FreshBooks when the file was uploaded.
     * @return FileUpload Object containing the file name, content type, and stream of data.
     */
    public function get(string $jwt): FileUpload
    {
        return $this->makeGetFileRequest($this->getUrl(jwt: $jwt));
    }

    /**
     * Upload a file to FreshBooks.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  resource File resource to upload
     * @return FileUpload Object containing the JWT, file name, content type.
     */
    public function upload(string $accountId, $file): FileUpload
    {
        $url = $this->getUrl($accountId);
        return  $this->makeUploadRequest($url, $file);
    }
}
