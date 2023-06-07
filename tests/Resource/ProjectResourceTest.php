<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Builder\FilterBuilder;
use amcintosh\FreshBooks\Builder\IncludesBuilder;
use amcintosh\FreshBooks\Builder\PaginateBuilder;
use amcintosh\FreshBooks\Model\Project;
use amcintosh\FreshBooks\Model\ProjectList;
use amcintosh\FreshBooks\Model\VisState;
use amcintosh\FreshBooks\Resource\ProjectResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class ProjectResourceTest extends TestCase
{
    use BaseResourceTest;

    private int $businessId;

    protected function setUp(): void
    {
        $this->businessId = 99999;
    }

    public function testGet(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => ['id' => $projectId]]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->get($this->businessId, $projectId);

        $this->assertSame($projectId, $project->id);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects/12345', $request->getRequestTarget());
    }

    public function testGetWithIncludes(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => ['id' => $projectId]]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $includes = (new IncludesBuilder())->include('include_some_thing');
        $project = $resource->get($this->businessId, $projectId, $includes);

        $this->assertSame($projectId, $project->id);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(
            '/projects/business/99999/projects/12345?include_some_thing=true',
            $request->getRequestTarget()
        );
    }

    public function testGetWrongSuccessContent(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(200, ['foo' => 'bar']);

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $resource->get($this->businessId, $projectId);
    }

    public function testGetWrongErrorContent(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(400, ['foo' => 'bar']);

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Unknown error');

        $resource->get($this->businessId, $projectId);
    }

    public function testGetNoPermission(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            401,
            [
                "message" => "The server could not verify that you are authorized to access the URL requested. " .
                "You either supplied the wrong credentials (e.g. a bad password), or your browser doesn't " .
                "understand how to supply the credentials required."
            ]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage(
            'The server could not verify that you are authorized to access the URL requested.'
        );

        $resource->get($this->businessId, $projectId);
    }

    public function testList(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            [
                'projects' => [
                    ['id' => $projectId],
                ],
                'meta' => [
                    'page' => 1,
                    'per_page' => 15,
                    'pages' => 1,
                    'total' => 1
                ]
            ]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $projects = $resource->list($this->businessId);

        $this->assertSame($projectId, $projects->projects[0]->id);
        $this->assertSame(1, $projects->meta->page);
        $this->assertSame(15, $projects->meta->perPage);
        $this->assertSame(1, $projects->meta->pages);
        $this->assertSame(1, $projects->meta->total);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects', $request->getRequestTarget());
    }

    public function testListNoRecords(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            [
                'projects' => [],
                'meta' => [
                    'page' => 1,
                    'per_page' => 15,
                    'pages' => 0,
                    'total' => 0
                ]
            ]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $projects = $resource->list($this->businessId);

        $this->assertSame([], $projects->projects);
        $this->assertSame(1, $projects->meta->page);
        $this->assertSame(15, $projects->meta->perPage);
        $this->assertSame(0, $projects->meta->pages);
        $this->assertSame(0, $projects->meta->total);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects', $request->getRequestTarget());
    }

    public function testListPaged(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            [
                'projects' => [
                    ['id' => 12345],
                ],
                'meta' => [
                    'page' => 1,
                    'per_page' => 2,
                    'pages' => 2,
                    'total' => 2
                ]
            ]
        );
        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $pages = new PaginateBuilder(1, 2);
        $projects = $resource->list($this->businessId, [$pages]);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects?page=1&per_page=2', $request->getRequestTarget());
    }

    public function testListFiltered(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            [
                'projects' => [
                    ['id' => 12345],
                ],
                'meta' => [
                    'page' => 1,
                    'per_page' => 15,
                    'pages' => 1,
                    'total' => 1
                ]
            ]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $filters = (new FilterBuilder())->equals('title', 'Awesome Project');
        $projects = $resource->list($this->businessId, [$filters]);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(
            '/projects/business/99999/projects?title=Awesome%20Project',
            $request->getRequestTarget()
        );
    }

    public function testCreateByModel(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $model = new Project();
        $model->title = $title;

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->create($this->businessId, model: $model);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects', $request->getRequestTarget());
    }

    public function testCreateByData(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $data = array('title' => $title);

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->create($this->businessId, data: $data);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects', $request->getRequestTarget());
    }

    public function testCreateWithIncludes(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $model = new Project();
        $model->title = $title;

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $includes = (new IncludesBuilder())->include('include_logged_duration');
        $project = $resource->create($this->businessId, model: $model, includes: $includes);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(
            '/projects/business/99999/projects?include_logged_duration=true',
            $request->getRequestTarget()
        );
    }

    public function testCreateValidationErrors(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            422,
            [
                'errno' => 2001,
                'error' => [
                    'title' => 'field required',
                    'description' => 'field required'
                ]
            ]
        );

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);

        try {
            $resource->create($this->businessId, data: []);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Error: description field required', $e->getMessage());
            $this->assertSame(422, $e->getCode());
            $this->assertSame(
                [
                    ['title' => 'field required'],
                    ['description' => 'field required']
                ],
                $e->getErrorDetails()
            );
        }
    }

    public function testUpdateByModel(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $model = new Project();
        $model->title = $title;

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->update($this->businessId, $projectId, model: $model);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects/12345', $request->getRequestTarget());
    }

    public function testUpdateByData(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $data = array('title' => $title);

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->update($this->businessId, $projectId, data: $data);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects/12345', $request->getRequestTarget());
    }

    public function testUpdateWithIncludes(): void
    {
        $projectId = 12345;
        $title = 'Some Test Project';

        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['project' => [
                'id' => $projectId,
                'title' => $title
            ]]
        );
        $model = new Project();
        $model->title = $title;

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $includes = (new IncludesBuilder())->include('include_logged_duration');
        $project = $resource->update($this->businessId, $projectId, model: $model, includes: $includes);

        $this->assertSame($projectId, $project->id);
        $this->assertSame($title, $project->title);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame(
            '/projects/business/99999/projects/12345?include_logged_duration=true',
            $request->getRequestTarget()
        );
    }

    public function testDelete(): void
    {
        $projectId = 12345;
        $mockHttpClient = $this->getMockHttpClient(204, []);

        $resource = new ProjectResource($mockHttpClient, 'projects', 'projects', Project::class, ProjectList::class);
        $project = $resource->delete($this->businessId, $projectId);

        $this->assertSame(null, $project);
        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/projects/business/99999/projects/12345', $request->getRequestTarget());
    }
}
