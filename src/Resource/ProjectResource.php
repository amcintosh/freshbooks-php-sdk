<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Builder\IncludesBuilder;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ListModel;
use amcintosh\FreshBooks\Model\VisState;

class ProjectResource extends BaseResource
{
    private HttpClient $httpClient;
    private string $singleResourcePath;
    private string $listResourcePath;
    private string $singleModel;
    private string $listModel;

    public function __construct(
        HttpClient $httpClient,
        string $singleResourcePath,
        string $listResourcePath,
        string $singleModel,
        string $listModel
    ) {
        $this->httpClient = $httpClient;
        $this->singleModel = $singleModel;
        $this->listModel = $listModel;
        $this->singleResourcePath = $singleResourcePath;
        $this->listResourcePath = $listResourcePath;
    }

    /**
     * The the url to the accounting resource.
     *
     * @param  int $businessId
     * @param  int $resourceId
     * @param  bool $isList
     * @return string
     */
    private function getUrl(int $businessId, int $resourceId = null, bool $isList = false): string
    {
        if (!is_null($resourceId)) {
            return "/projects/business/{$businessId}/{$this->singleResourcePath}/{$resourceId}";
        }
        if ($isList) {
            return "/projects/business/{$businessId}/{$this->listResourcePath}";
        }
        return "/projects/business/{$businessId}/{$this->singleResourcePath}";
    }

    /**
     * Parse the json response for project endpoint errors and create a FreshBooksException from it.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function createResponseError(int $statusCode, array $responseData, string $rawRespone): void
    {
        $message = $responseData['message'] ?? "Unknown error";
        $errorCode = $responseData['code'] ?? null;
        $errorDetails = null;

        if (array_key_exists('error', $responseData) && is_array($responseData['error'])) {
            $errorDetails = [];
            foreach ($responseData['error'] as $errorKey => $errorDetail) {
                $errorDetails[] = [$errorKey => $errorDetail];
                $message = 'Error: ' . $errorKey . ' ' . $errorDetail;
            }
        } elseif (array_key_exists('error', $responseData)) {
            $message = $responseData['error'];
        }
        throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode, $errorDetails);
    }

    /**
     * Make a request against the accounting resource and return an array of the json response.
     * Throws a FreshBooksException if the response is not a 200 or if the response cannot be parsed.
     *
     * @param  string $method
     * @param  string $url
     * @param  array $data
     * @return array
     */
    private function makeRequest(string $method, string $url, array $data = null): array
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
        if (
            !array_key_exists($this->singleModel::RESPONSE_FIELD, $responseData) &&
            !array_key_exists($this->listModel::RESPONSE_FIELD, $responseData)
        ) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }
        return $responseData;
    }

    /**
     * Get a single resource with the corresponding id.
     *
     * @param  int $businessId The business id
     * @param  int $resourceId Id of the resource to return
     * @return DataTransferObject The result model
     */
    public function get(int $businessId, int $resourceId, ?IncludesBuilder $includes = null): DataTransferObject
    {
        $url = $this->getUrl($businessId, $resourceId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Get a list of resources.
     *
     * @param  int $businessId The business id
     * @param  array $builders (Optional) List of builder objects for filters, pagination, etc.
     * @return DataTransferObject The list result model
     */
    public function list(int $businessId, ?array $builders = null): DataTransferObject
    {
        $url = $this->getUrl($businessId, isList: true) . $this->buildQueryString($builders);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->listModel($result);
    }

    /**
     * Create a resource from either an array or a DataModel object.
     *
     * @param  int $businessId The business id
     * @param  DataModel $model (Optional) The model to create
     * @param  array $data (Optional) The data to create the model with
     * @return DataTransferObject Model of the new resource's response data.
     */
    public function create(
        int $businessId,
        DataModel $model = null,
        array $data = null,
        ?IncludesBuilder $includes = null
    ): DataTransferObject {
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array($this->singleModel::RESPONSE_FIELD => $data);
        $url = $this->getUrl($businessId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::POST, $url, $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Update a resource from either an array or a DataModel object.
     *
     * @param  int $businessId The business id
     * @param  int $resourceId Id of the resource to update
     * @param  DataModel $model (Optional) The model to update
     * @param  array $data (Optional) The data to update the model with
     * @return DataTransferObject Model of the updated resource's response data.
     */
    public function update(
        int $businessId,
        int $resourceId,
        DataModel $model = null,
        array $data = null,
        ?IncludesBuilder $includes = null
    ): DataTransferObject {
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array($this->singleModel::RESPONSE_FIELD => $data);
        $url = $this->getUrl($businessId, $resourceId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::PUT, $url, $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Delete a resource.
     *
     * Note: Most FreshBooks resources are soft-deleted,
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     *
     * @param  int $businessId The business id
     * @param  int $resourceId Id of the resource to delete
     */
    public function delete(int $businessId, int $resourceId): void
    {
        $this->makeRequest(self::DELETE, $this->getUrl($businessId, $resourceId));
    }
}
