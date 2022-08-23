<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\Project;

final class ProjectTest extends TestCase
{
    private $sampleProjectData = '{
        "project": {
            "id": 654321,
            "title": "Awesome Project",
            "description": null,
            "due_date": "2021-04-16",
            "client_id": null,
            "internal": true,
            "budget": null,
            "fixed_price": "600.00",
            "rate": "5.00",
            "billing_method": "project_rate",
            "project_type": "hourly_rate",
            "project_manager_id": null,
            "active": true,
            "complete": false,
            "sample": false,
            "created_at": "2020-09-13T01:07:51",
            "updated_at": "2020-09-13T03:10:13",
            "logged_duration": null,
            "services": [
                {
                    "business_id": 439000,
                    "id": 154,
                    "name": "Some Service",
                    "billable": true,
                    "vis_state": 0
                },
                {
                    "business_id": 439000,
                    "id": 155,
                    "name": "A new service",
                    "billable": true,
                    "vis_state": 0
                }
            ],
            "billed_amount": "0.00",
            "billed_status": "unbilled",
            "retainer_id": null,
            "expense_markup": "0",
            "group": {
                "id": 764645,
                "members": [
                    {
                        "id": 4652287,
                        "identity_id": 65001,
                        "role": "owner",
                        "first_name": "Gordon",
                        "last_name": "Shumway",
                        "email": "gordon.shumway@AmericanCyanamid.com",
                        "company": "American Cyanamid",
                        "active": true
                    }
                ],
                "pending_invitations": []
            }
        }
    }';

    public function testProjectFromResponse(): void
    {
        $projectData = json_decode($this->sampleProjectData, true);

        $project = new Project($projectData[Project::RESPONSE_FIELD]);

        $this->assertSame(654321, $project->id);
        $this->assertSame(true, $project->active);
        $this->assertEquals(Decimal::create('0.00'), $project->billedAmount);
        $this->assertSame('unbilled', $project->billedStatus);
        $this->assertSame('project_rate', $project->billingMethod);
        $this->assertSame(null, $project->clientId);
        $this->assertSame(false, $project->complete);
        $this->assertEquals(new DateTime('2020-09-13T01:07:51Z'), $project->createdAt);
        $this->assertSame(null, $project->description);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $project->dueDate);
        $this->assertSame('0', $project->expenseMarkup);
        $this->assertEquals(Decimal::create('600.00'), $project->fixedPrice);
        $this->assertSame(true, $project->internal);
        $this->assertSame(null, $project->loggedDuration);
        $this->assertSame(null, $project->projectManagerId);
        $this->assertSame('hourly_rate', $project->projectType);
        $this->assertEquals(Decimal::create('5.00'), $project->rate);
        $this->assertSame(null, $project->retainerId);
        $this->assertSame('Awesome Project', $project->title);
        $this->assertEquals(new DateTime('2020-09-13T03:10:13Z'), $project->updatedAt);
        $this->assertSame(764645, $project->group->id);

        $groupMember = $project->group->members[0];
        $this->assertSame(4652287, $groupMember->id);
        $this->assertSame(true, $groupMember->active);
        $this->assertSame('American Cyanamid', $groupMember->company);
        $this->assertSame('gordon.shumway@AmericanCyanamid.com', $groupMember->email);
        $this->assertSame('Gordon', $groupMember->firstName);
        $this->assertSame(65001, $groupMember->identityId);
        $this->assertSame('Shumway', $groupMember->lastName);
        $this->assertSame('owner', $groupMember->role);

        $service = $project->services[0];
        $this->assertSame(154, $service->id);
        $this->assertSame(439000, $service->businessId);
        $this->assertSame('Some Service', $service->name);
        $this->assertSame(true, $service->billable);
        $this->assertSame(0, $service->visState);
    }

    public function testProjectGetContent(): void
    {
        $projectData = json_decode($this->sampleProjectData, true);
        $project = new Project($projectData['project']);
        $this->assertSame([
            'active' => true,
            'billing_method' => 'project_rate',
            'complete' => false,
            'expense_markup' => '0',
            'internal' => true,
            'project_type' => 'hourly_rate',
            'title' => 'Awesome Project',
            'due_date' => '2021-04-16',
            'fixed_price' => '600.00',
            'rate' => '5.00'
        ], $project->getContent());
    }
}
