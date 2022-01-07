<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksClientConfigException;
use amcintosh\FreshBooks\FreshBooksClientConfig;
use amcintosh\FreshBooks\Model\AuthorizationToken;
use amcintosh\FreshBooks\Tests\Util\MockFreshBooksClient;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class FreshBooksClientTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testGetHeaders(): void
    {
        $expectedHeaders = [
            'Authorization' => 'Bearer some_token',
            'User-Agent' => 'some_ua',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        $conf = new FreshBooksClientConfig(accessToken: 'some_token', userAgent: 'some_ua');

        $client = new MockFreshBooksClient('some_client_id', $conf);
        $this->assertSame($expectedHeaders, $client->accessGetHeaders());
    }

    public function testGetAuthRequestUrl(): void
    {
        $conf = new FreshBooksClientConfig(redirectUri: 'https://example.com');
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->assertSame(
            'https://auth.freshbooks.com/oauth/authorize?' .
            'client_id=some_client_id&response_type=code&redirect_uri=https%3A%2F%2Fexample.com',
            $client->getAuthRequestUri()
        );
    }

    public function testGetAuthRequestUrlWithScopes(): void
    {
        $conf = new FreshBooksClientConfig(redirectUri: 'https://example.com');
        $client = new MockFreshBooksClient('some_client_id', $conf);
        $scopes = ['some:scope', 'another:scope'];

        $this->assertSame(
            'https://auth.freshbooks.com/oauth/authorize?' .
            'client_id=some_client_id&response_type=code&redirect_uri=https%3A%2F%2Fexample.com' .
            '&scope=some%3Ascope+another%3Ascope',
            $client->getAuthRequestUri($scopes)
        );
    }

    public function testGetAuthRequestUrlRedirectNotProvided(): void
    {
        $conf = new FreshBooksClientConfig();
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->expectException(FreshBooksClientConfigException::class);
        $this->expectExceptionMessage('redirectUri must be configured');

        $client->getAuthRequestUri();
    }

    public function testGetAccessToken(): void
    {
        $expected = [
            'client_id' => 'some_client_id',
            'client_secret' => 'some_client_secret',
            'redirect_uri' => 'https://example.com',
            'grant_type' => 'authorization_code',
            'code' => '1234',
        ];
        $returnToken = new AuthorizationToken([
            'created_at' => time(),
            'expires_in' => 3600
        ]);
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldReceive('getToken')
            ->with($expected)
            ->andReturn($returnToken);

        $conf = new FreshBooksClientConfig(
            clientSecret: 'some_client_secret',
            redirectUri: 'https://example.com'
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $client->getAccessToken('1234');
    }

    public function testGetAccessTokenSecretRequired(): void
    {
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldNotReceive('getToken');

        $conf = new FreshBooksClientConfig(
            redirectUri: 'https://example.com'
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->expectException(FreshBooksClientConfigException::class);
        $this->expectExceptionMessage('clientSecret must be configured');

        $client->getAccessToken('1234');
    }

    public function testGetAccessTokenRedirectUriRequired(): void
    {
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldNotReceive('getToken');

        $conf = new FreshBooksClientConfig(
            clientSecret: 'some_client_secret',
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->expectException(FreshBooksClientConfigException::class);
        $this->expectExceptionMessage('redirectUri must be configured');

        $client->getAccessToken('1234');
    }

    public function testRefreshAccessToken(): void
    {
        $expected = [
            'client_id' => 'some_client_id',
            'client_secret' => 'some_client_secret',
            'redirect_uri' => 'https://example.com',
            'grant_type' => 'refresh_token',
            'refresh_token' => '1234',
        ];
        $returnToken = new AuthorizationToken([
            'created_at' => time(),
            'expires_in' => 3600
        ]);
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldReceive('getToken')
            ->with($expected)
            ->andReturn($returnToken);

        $conf = new FreshBooksClientConfig(
            clientSecret: 'some_client_secret',
            redirectUri: 'https://example.com'
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $client->refreshAccessToken('1234');
    }

    public function testRefreshAccessTokenFromConfiguration(): void
    {
        $expected = [
            'client_id' => 'some_client_id',
            'client_secret' => 'some_client_secret',
            'redirect_uri' => 'https://example.com',
            'grant_type' => 'refresh_token',
            'refresh_token' => '1234',
        ];
        $returnToken = new AuthorizationToken([
            'created_at' => time(),
            'expires_in' => 3600
        ]);
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldReceive('getToken')
            ->with($expected)
            ->andReturn($returnToken);

        $conf = new FreshBooksClientConfig(
            clientSecret: 'some_client_secret',
            redirectUri: 'https://example.com',
            refreshToken: '1234'
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $client->refreshAccessToken();
    }

    public function testRefreshAccessTokenCurrentRefreshRequired(): void
    {
        $authMock = Mockery::mock('overload:amcintosh\FreshBooks\Resource\AuthResource');
        $authMock->shouldNotReceive('getToken');

        $conf = new FreshBooksClientConfig(
            clientSecret: 'some_client_secret',
            redirectUri: 'https://example.com',
        );
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->expectException(FreshBooksClientConfigException::class);
        $this->expectExceptionMessage('refreshToken must be configured');

        $client->refreshAccessToken();
    }
}
