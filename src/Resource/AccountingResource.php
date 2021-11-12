<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ListModel;
use amcintosh\FreshBooks\Model\VisState;

class AccountingResource extends BaseResource
{

    public function __construct(
        HttpClient $httpClient,
        string $accountingPath,
        string $singleModel,
        string $listModel,
        bool $deleteViaUpdate = true
    ) {
        parent::__construct($singleModel, $listModel);
        $this->httpClient = $httpClient;
        $this->accountingPath = $accountingPath;
        $this->deleteViaUpdate = $deleteViaUpdate;
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

        if (is_null($responseData) || !array_key_exists('response', $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }

        $responseData = $responseData['response'];

        if ($statusCode >= 400) {
            $this->createResponseError($statusCode, $responseData, $contents);
        }

        if (array_key_exists('result', $responseData)) {
            return $responseData['result'];
        }
        return $responseData;
    }

    /**
     * Parse the json response from the accounting endpoint and create a FreshBooksException from it.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    private function createResponseError(int $statusCode, array $responseData, string $rawRespone): void
    {
        if (!array_key_exists('errors', $responseData)) {
            throw new FreshBooksException('Unknown error', $statusCode, null, $rawRespone);
        }
        $errors = $responseData['errors'];
        if (array_key_exists(0, $errors)) {
            $message = $errors[0]['message'] ?? 'Unknown error2';
            $errorCode = $errors[0]['errno'] ?? null;
            throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode);
        }
        $message = $errors['message'] ?? 'Unknown error';
        $errorCode = $errors['errno'] ?? null;
        throw new FreshBooksException($message, $statusCode, null, $rawRespone, $errorCode);
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
        $result = $this->makeRequest(self::GET, $this->getUrl($accountId, $resourceId));
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
        $queryString = $this->buildQueryString($builders);
        $result = $this->makeRequest(self::GET, $this->getUrl($accountId) . $queryString);
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
    public function create(string $accountId, DataModel $model = null, array $data = null): DataTransferObject
    {
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array('client' => $data);
        $result = $this->makeRequest(self::POST, $this->getUrl($accountId), $data);
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
        array $data = null
    ): DataTransferObject {
        if (!is_null($model)) {
            $data = $model->getContent();
        }
        $data = array('client' => $data);
        $result = $this->makeRequest(self::PUT, $this->getUrl($accountId, $resourceId), $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    public function delete(string $accountId, int $resourceId): DataTransferObject
    {
        if ($this->deleteViaUpdate) {
            $data = array('client' => ['vis_state' => VisState::DELETED]);
            $result = $this->makeRequest(self::PUT, $this->getUrl($accountId, $resourceId), $data);
        } else {
            $result = $this->makeRequest(self::DELETE, $this->getUrl($accountId, $resourceId));
        }
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }
}
