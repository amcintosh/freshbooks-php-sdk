<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Resource\UploadResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class UploadResourceTest extends TestCase
{
    use BaseResourceTest;

    public string $accountId;

    protected function setUp(): void
    {
        $this->accountId = 'ACM123';
    }


    public function testGet(): void
    {
        $fileName = 'sample_logo.png';
        $contentType = 'image/png';
        $mockHttpClient = $this->getMockFileHttpClient(
            200,
            'tests/Util/sample_logo.png',
            $fileName,
            $contentType
        );

        $resource = new UploadResource($mockHttpClient, 'images', 'image');
        $image = $resource->get($this->accountId, 'SOME_JWT');

        $this->assertSame($fileName, $image->fileName);
        $this->assertSame($contentType, $image->mediaType);
        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/uploads/images/ACM123', $request->getRequestTarget());
    }

    public function testGetWrongErrorContent(): void
    {
        $mockHttpClient = $this->getMockHttpClient(400, ['foo' => 'bar']);

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Unknown error');

        $resource->get('ACM123');
    }

    public function testGetNotFound(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            404,
            ['error' => 'File not found']
        );

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('File not found');

        $resource->get('ACM123');
    }

    public function testGetUnknownError(): void
    {
        $mockHttpClient = $this->getMockHttpClient(500);

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Unknown error');

        $resource->get('ACM123');
    }


    public function testUpload(): void
    {
        $jwt = 'some_jwt';
        $fileName = 'upload-x123';
        $contentType = 'image/png';
        $mockHttpClient = $this->getMockHttpClient(
            200,
            [
                'image' => [
                    'filename' => $fileName,
                    'public_id' => $jwt,
                    'jwt' => $jwt,
                    'media_type' => $contentType,
                    'uuid' => 'some_uuid'
                ],
                'link' => "https://my.freshbooks.com/service/uploads/images/{$jwt}"
            ]
        );

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $imageData = $resource->upload($this->accountId, fopen('tests/Util/sample_logo.png', 'r'));

        $this->assertSame($fileName, $imageData->fileName);
        $this->assertSame($contentType, $imageData->mediaType);
        $this->assertSame($jwt, $imageData->jwt);
        $this->assertSame('https://my.freshbooks.com/service/uploads/images/some_jwt', $imageData->link);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/uploads/account/ACM123/images', $request->getRequestTarget());
    }

    public function testUploadBadRequest(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            400,
            ['error' => 'Content required']
        );

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Content required');

        $imageData = $resource->upload($this->accountId, fopen('tests/Util/sample_logo.png', 'r'));
    }

    public function testUploadUnknownError(): void
    {
        $mockHttpClient = $this->getMockHttpClient(500);

        $resource = new UploadResource($mockHttpClient, 'images', 'image');

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Unknown error');

        $imageData = $resource->upload($this->accountId, fopen('tests/Util/sample_logo.png', 'r'));
    }
}
