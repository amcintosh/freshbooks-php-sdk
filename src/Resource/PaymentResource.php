<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\DataModelLegacy;

class PaymentResource extends BaseResource
{
    private HttpClient $httpClient;
    private string $resourcePath;
    private ?string $subResourcePath;
    private ?string $defaultsPath;
    private ?string $staticPathParams;
    private string $model;

    public function __construct(
        HttpClient $httpClient,
        string $resourcePath,
        string $model,
        ?string $subResourcePath = null,
        ?string $defaultsPath = null,
        ?string $staticPathParams = null,
    ) {
        $this->httpClient = $httpClient;
        $this->resourcePath = $resourcePath;
        $this->subResourcePath = $subResourcePath;
        $this->defaultsPath = $defaultsPath;
        $this->staticPathParams = $staticPathParams;
        $this->model = $model;
    }

    /**
     * The the url to the payment resource.
     *
     * @param  int $accountId
     * @param  int $resourceId
     * @param  bool $isList
     * @return string
     */
    private function getUrl(string $accountId, ?int $resourceId = null): string
    {
        if (!is_null($resourceId) && !is_null($this->subResourcePath)) {
            return "/payments/account/{$accountId}/{$this->resourcePath}/{$resourceId}/{$this->subResourcePath}";
        } else {
            $url = "/payments/account/{$accountId}/{$this->defaultsPath}";
            if (!is_null($this->staticPathParams) && !is_null($this->subResourcePath)) {
                $url .= "?{$this->staticPathParams}";
            }
            return $url;
        }
    }

    /**
     * Parse the json response for payments endpoint errors and create a FreshBooksException from it.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function createResponseError(int $statusCode, array $responseData, string $rawRespone): void
    {
        $message = $responseData['message'] ?? 'Unknown error';
        $errorDetails = null;

        if (array_key_exists('errors', $responseData) && is_array($responseData['errors'])) {
            $errorDetails = [];
            foreach ($responseData['errors'] as $errorKey => $errorDetail) {
                $errorDetails[] = [$errorKey => $errorDetail];
                $message = 'Error: ' . $errorKey . ' ' . $errorDetail;
            }
        } elseif (array_key_exists('error', $responseData)) {
            $message = $responseData['error'];
        }
        throw new FreshBooksException($message, $statusCode, null, $rawRespone, null, $errorDetails);
    }

    /**
     * Make a request against the payments resource and return an array of the json response.
     * Throws a FreshBooksException if the response is not a 200 or if the response cannot be parsed.
     *
     * @param  string $method
     * @param  string $url
     * @param  array $data
     * @return array
     */
    private function makeRequest(string $method, string $url, ?array $data = null): array
    {
        if (!is_null($data)) {
            $data = json_encode($data);
        }
        $response = $this->httpClient->send($method, $url, [], $data);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 204 && $method == self::DELETE) {
            return [];
        }
        try {
            $contents = $response->getBody()->getContents();
            $responseData = json_decode($contents, true);
        } catch (JSONDecodeError $e) {
            throw new FreshBooksException('Failed to parse response', $statusCode, $e, $contents);
        }

        if ($statusCode >= 400) {
            $this->createResponseError($statusCode, $responseData, $contents);
        }
        if (!array_key_exists($this->model::RESPONSE_FIELD, $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }
        return $responseData;
    }

    /**
     * Get the default settings for an account resource.
     *
     * @param  string $accountId The alpha-numeric account id
     * @return DataTransferObject The result model with the default data
     */
    public function defaults(string $accountId): DataTransferObject
    {
        $url = $this->getUrl($accountId);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->model($result[$this->model::RESPONSE_FIELD]);
    }

    /**
     * Get a single resource with the corresponding id.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to return
     * @return DataTransferObject The result model
     */
    public function get(string $accountId, int $resourceId): DataTransferObject
    {
        $url = $this->getUrl($accountId, $resourceId);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->model($result[$this->model::RESPONSE_FIELD]);
    }

    /**
     * Create a resource from either an array or a DataModelLegacy object.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  DataModelLegacy $model (Optional) The model to create
     * @param  array $data (Optional) The data to create the model with
     * @return DataTransferObject Model of the new resource's response data.
     */
    public function create(
        string $accountId,
        int $resourceId,
        ?DataModelLegacy $model = null,
        ?array $data = null
    ): DataTransferObject {
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $url = $this->getUrl($accountId, $resourceId);
        $result = $this->makeRequest(self::POST, $url, $data);
        return new $this->model($result[$this->model::RESPONSE_FIELD]);
    }
}
