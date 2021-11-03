<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\Client;

class AccountingResource extends BaseResource
{

    public function __construct(HttpClient $httpClient, string $accountingPath, string $model)
    {
        parent::__construct($model);
        $this->httpClient = $httpClient;
        $this->accountingPath = $accountingPath;
    }


    /**
     * getUrl
     *
     * @param  mixed $accountId
     * @param  mixed $resourceId
     * @return string
     */
    private function getUrl(string $accountId, int $resourceId = null): string
    {
        if (!is_null($resourceId)) {
            return "/accounting/account/{$accountId}/{$this->accountingPath}/{$resourceId}";
        }
        return "/accounting/account/{$accountId}/{$this->accountingPath}";
    }

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
     * createResponseError
     *
     * @param  mixed $statusCode
     * @param  mixed $responseData
     * @param  mixed $rawRespone
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

    public function get(string $accountId, int $resourceId): DataTransferObject
    {
        $data = $this->makeRequest(self::GET, $this->getUrl($accountId, $resourceId));
        return new $this->model($data[$this->getModelName()]);
    }

    public function create(string $accountId, array $data): DataTransferObject
    {
        $data = $this->makeRequest(self::POST, $this->getUrl($accountId), $data);
        return new $this->model($data[$this->getModelName()]);
    }
}
