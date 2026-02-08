<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Builder\IncludesBuilder;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Exception\FreshBooksNotImplementedException;
use amcintosh\FreshBooks\Model\DataModelLegacy;
use amcintosh\FreshBooks\Model\ListModel;
use amcintosh\FreshBooks\Model\VisState;

/**
 * Resource for calls to /events endpoints.
 *
 * @package amcintosh\FreshBooks\Resource
 */
class EventsResource extends AccountingResource
{
    /**
     * The the url to the events resource.
     *
     * @param  string $accountId
     * @param  int $resourceId
     * @return string
     */
    protected function getUrl(string $accountId, ?int $resourceId = null): string
    {
        if (!is_null($resourceId)) {
            return "/events/account/{$accountId}/{$this->accountingPath}/{$resourceId}";
        }
        return "/events/account/{$accountId}/{$this->accountingPath}";
    }

    /**
     * Create a FreshBooksException from the json response from the events endpoint.
     *
     * @param  int $statusCode HTTP status code
     * @param  array $responseData The json-parsed response
     * @param  string $rawRespone The raw response body
     * @return void
     */
    protected function handleError(int $statusCode, array $responseData, string $rawRespone): void
    {
        if (!array_key_exists('message', $responseData)) {
            throw new FreshBooksException('Unknown error', $statusCode, null, $rawRespone);
        }

        $message = $responseData['message'];
        $details = [];
        if (array_key_exists('details', $responseData)) {
            $details = $responseData['details'];
        }

        throw new FreshBooksException($message, $statusCode, null, $rawRespone, null, $details);
    }

    /**
     * Tell FreshBooks to resend the verification webhook for the callback
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to update
     * @return DataTransferObject Model of the updated resource's response data.
     */
    public function resendVerification(string $accountId, int $resourceId): DataTransferObject
    {
        $data = array($this->singleModel::RESPONSE_FIELD => array("resend" => true));
        $result = $this->makeRequest(self::PUT, $this->getUrl($accountId, $resourceId), $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }

    /**
     * Verify webhook callback by making a put request
     *
     * @param  string $accountId The alpha-numeric account id
     * @param  int $resourceId Id of the resource to update
     * @param  string The string verifier received by the webhook callback URI
     * @return DataTransferObject Model of the updated resource's response data.
     */
    public function verify(
        string $accountId,
        int $resourceId,
        string $verifier,
    ): DataTransferObject {
        $data = array($this->singleModel::RESPONSE_FIELD => array("verifier" => $verifier));
        $result = $this->makeRequest(self::PUT, $this->getUrl($accountId, $resourceId), $data);
        return new $this->singleModel($result[$this->singleModel::RESPONSE_FIELD]);
    }
}
