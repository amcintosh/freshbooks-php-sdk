<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\ProjectList;

final class ProjectListTest extends TestCase
{
    private $sampleProjectData = '{
        "meta": {
            "sort": [],
            "total": 3,
            "per_page": 15,
            "page": 1,
            "pages": 1
        },
        "projects": [
            {
                "id": 654321,
                "title": "Awesome Project",
                "description": null,
                "due_date": null,
                "client_id": null,
                "internal": true,
                "budget": null,
                "fixed_price": null,
                "rate": null,
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
                "group_id": 764645
            },
            {
                "id": 654322,
                "title": "Super Project",
                "description": null,
                "due_date": null,
                "client_id": 12345,
                "internal": false,
                "budget": null,
                "fixed_price": null,
                "rate": null,
                "billing_method": "project_rate",
                "project_type": "fixed_price",
                "project_manager_id": null,
                "active": true,
                "complete": false,
                "sample": false,
                "created_at": "2018-04-26T00:28:34",
                "updated_at": "2018-04-26T00:28:34",
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
                "group_id": 764645
            },
            {
                "id": 654323,
                "title": "Bad Project",
                "description": null,
                "due_date": null,
                "client_id": null,
                "internal": false,
                "budget": null,
                "fixed_price": null,
                "rate": null,
                "billing_method": "project_rate",
                "project_type": "fixed_price",
                "project_manager_id": null,
                "active": true,
                "complete": false,
                "sample": false,
                "created_at": "2019-06-24T10:24:59",
                "updated_at": "2019-06-24T10:27:15",
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
                "group_id": 764645
            }
        ]
    }';

    public function testProjectFromResponse(): void
    {
        $projectData = json_decode($this->sampleProjectData, true);

        $projects = new ProjectList($projectData);

        $this->assertSame(1, $projects->pages()->page);
        $this->assertSame(1, $projects->pages()->pages);
        $this->assertSame(15, $projects->pages()->perPage);
        $this->assertSame(3, $projects->pages()->total);

        $this->assertSame(654321, $projects->projects[0]->id);
        $this->assertSame('Awesome Project', $projects->projects[0]->title);

        $this->assertSame(654322, $projects->projects[1]->id);
        $this->assertSame('Super Project', $projects->projects[1]->title);
    }
}
