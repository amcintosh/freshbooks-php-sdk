<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Builder\IncludesBuilder;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Exception\FreshBooksNotImplementedException;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ListModel;
use amcintosh\FreshBooks\Model\VisState;

class AccountingResource extends BaseResource
{
    private HttpClient $httpClient;
    private string $accountingPath;
    private string $singleModel;
    private string $listModel;
    private bool $deleteViaUpdate;
    private ?array $missingEndpoints;

    public function __construct(
        HttpClient $httpClient,
        string $accountingPath,
        string $singleModel,
        string $listModel,
        bool $deleteViaUpdate = true,
        array $missingEndpoints = null
    ) {
        $this->httpClient = $httpClient;
        $this->singleModel = $singleModel;
        $this->listModel = $listModel;
        $this->accountingPath = $accountingPath;
        $this->deleteViaUpdate = $deleteViaUpdate;
        $this->missingEndpoints = $missingEndpoints;
    }

    /**
     * The the url to the accounting resource.
     *
     * @param  string $accountId
     * @param  int $resourceId
     * @return string
     */
    private function getUrl(string $accountId, int $resourceId = null): string
    {
        if (!is_null($resourceId)) {
            return "/accounting/account/{$accountId}/{$this->accountingPath}/{$resourceId}";
        }
        return "/accounting/account/{$accountId}/{$this->accountingPath}";
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

        try {
            $contents = $response->getBody()->getContents();
            $responseData = json_decode($contents, true);
        } catch (JSONDecodeError $e) {
            throw new FreshBooksException('Failed to parse response', $statusCode, $e, $contents);
        }

        if ($statusCode >= 400) {
            $this->handleError($statusCode, $responseData, $contents);
        }

        if (is_null($responseData) || !array_key_exists('response', $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }

        $responseData = $responseData['response'];

        if (array_key_exists('result', $responseData)) {
            return $responseData['result'];
        }
        return $responseData;
    }

    /**
     * Parse the json response for old-style accounting endpoint errors and create a FreshBooksException from it.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function createOldResponseError(int $statusCode, array $responseData, string $rawRespone): void
    {
        $errors = $responseData['response']['errors'];
        if (array_key_exists(0, $errors)) {
            $message = $errors[0]['message'] ?? 'Unknown error';
            $errorCode = $errors[0]['errno'] ?? null;
            throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode, $errors);
        }

        $message = $errors['message'] ?? 'Unknown error';
        $errorCode = $errors['errno'] ?? null;
        throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode, $errors);
    }

    /**
     * Parse the json response for new-style accounting endpoint errors and create a FreshBooksException from it.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function createNewResponseError(int $statusCode, array $responseData, string $rawRespone): void
    {
        $message = $responseData['message'];
        $details = [];

        foreach ($responseData['details'] as $detail) {
            if (in_array('type.googleapis.com/google.rpc.ErrorInfo', $detail)) {
                $errorCode = intval($detail['reason']) ?? null;
                if (array_key_exists('metadata', $detail)) {
                    $details[] = $detail['metadata'];
                    if (array_key_exists('message', $detail['metadata'])) {
                        $message = $detail['metadata']['message'];
                    }
                }
            }
        }
        throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode, $details);
    }

    /**
     * Create a FreshBooksException from the json response from the accounting endpoint.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function handleError(int $statusCode, array $responseData, string $rawRespone): void
    {
        if (array_key_exists('response', $responseData) && array_key_exists('errors', $responseData['response'])) {
            $this->createOldResponseError($statusCode, $responseData, $rawRespone);
        } elseif (array_key_exists('message', $responseData) && array_key_exists('code', $responseData)) {
            $this->createNewResponseError($statusCode, $responseData, $rawRespone);
        } else {
            throw new FreshBooksException('Unknown error', $statusCode, null, $rawRespone);
        }
    }

    private function rejectMissing(string $name): void
    {
        if (!is_null($this->missingEndpoints) && in_array($name, $this->missingEndpoints)) {
            throw new FreshBooksNotImplementedException($this->accountingPath, $name);
        }
    }

    /**
     * Get a single resource with the corresponding id.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to return
     * @return DataTransferObject The result model
     */
    public function get(string $accountId, int $resourceId, ?IncludesBuilder $includes = null): DataTransferObject
    {
        $this->rejectMissing('get');
        $url = $this->getUrl($accountId, $resourceId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Get a list of resources.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  array $builders (Optional) List of builder objects for filters, pagination, etc.
     * @return DataTransferObject The list result model
     */
    public function list(string $accountId, ?array $builders = null): DataTransferObject
    {
        $this->rejectMissing('list');
        $url = $this->getUrl($accountId) . $this->buildQueryString($builders);
        $result = $this->makeRequest(self::GET, $url);
        return new $this->listModel($result);
    }

    /**
     * Create a resource from either an array or a DataModel object.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  DataModel $model (Optional) The model to create
     * @param  array $data (Optional) The data to create the model with
     * @return DataTransferObject Model of the new resource's response data.
     */
    public function create(
        string $accountId,
        DataModel $model = null,
        array $data = null,
        ?IncludesBuilder $includes = null
    ): DataTransferObject {
        $this->rejectMissing('create');
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array($this->singleModel::RESPONSE_FIELD => $data);
        $url = $this->getUrl($accountId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::POST, $url, $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Update a resource from either an array or a DataModel object.
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to update
     * @param  DataModel $model (Optional) The model to update
     * @param  array $data (Optional) The data to update the model with
     * @return DataTransferObject Model of the updated resource's response data.
     */
    public function update(
        string $accountId,
        int $resourceId,
        DataModel $model = null,
        array $data = null,
        ?IncludesBuilder $includes = null
    ): DataTransferObject {
        $this->rejectMissing('update');
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array($this->singleModel::RESPONSE_FIELD => $data);
        $url = $this->getUrl($accountId, $resourceId) . $this->buildQueryString([$includes]);
        $result = $this->makeRequest(self::PUT, $url, $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Delete a resource.
     *
     * Note: Most FreshBooks resources are soft-deleted,
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to delete
     * @return DataTransferObject Null for some resources or the Model of the deleted resource's response data.
     */
    public function delete(string $accountId, int $resourceId): ?DataTransferObject
    {
        $this->rejectMissing('delete');
        if ($this->deleteViaUpdate) {
            $data = array($this->singleModel::RESPONSE_FIELD => ['vis_state' => VisState::DELETED]);
            $result = $this->makeRequest(self::PUT, $this->getUrl($accountId, $resourceId), $data);
            return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
        } else {
            $result = $this->makeRequest(self::DELETE, $this->getUrl($accountId, $resourceId));
            return null;
        }
    }
}
