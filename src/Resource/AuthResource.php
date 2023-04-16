<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

use Http\Client\HttpClient;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\AuthorizationToken;
use amcintosh\FreshBooks\Model\Identity;

class AuthResource extends BaseResource
{
    private HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * The the url to the resource.
     *
     * @param  string $accountId
     * @param  int $resourceId
     * @return string
     */
    private function getUrl(string $endpoint): string
    {
        return "/auth/api/v1/{$endpoint}";
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
            $message = $responseData['error_description'] ?? 'Returned an unexpected response';
            $errorCode = intval($responseData['error'] ?? 0);
            throw new FreshBooksException($message, $statusCode, null, $contents, $errorCode);
        }
        if (is_null($responseData) || !array_key_exists('response', $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }

        if (array_key_exists('response', $responseData)) {
            return $responseData['response'];
        }
        throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
    }

    /**
     * Make call to FreshBooks OAuth /token endpoint to fetch access_token and refresh_tokens.
     *
     * @param  mixed $authParams OAuth data payload
     * @return AuthorizationToken Object containing the access toekn, refresh token, and expiry details.
     */
    public function getToken(array $authParams): AuthorizationToken
    {
        $response = $this->httpClient->send(self::POST, '/auth/oauth/token', [], json_encode($authParams));

        $statusCode = $response->getStatusCode();
        try {
            $contents = $response->getBody()->getContents();
            $responseData = json_decode($contents, true);
        } catch (JSONDecodeError $e) {
            throw new FreshBooksException('Failed to parse response', $statusCode, $e, $contents);
        }
        if (!array_key_exists('access_token', $responseData)) {
            throw new FreshBooksException('Returned an unexpected response', $statusCode, null, $contents);
        }

        return new AuthorizationToken($responseData);
    }

    /**
     * Get the identity details of the currently authenticated user.
     *
     * @link https://www.freshbooks.com/api/me_endpoint
     *
     * @return Identity Result object with the authenticated user's identity and business details.
     */
    public function getMeEndpoint(): Identity
    {
        $result = $this->makeRequest(self::GET, $this->getUrl('users/me'));
        return new Identity($result);
    }
}
