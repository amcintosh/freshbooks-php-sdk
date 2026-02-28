<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Task;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Model\VisState;

final class TaskTest extends TestCase
{
    private $sampleTaskData = '{"task": {
        "billable": true,
        "description": "Piloting the project based on the expectations of the executive",
        "id": 159361,
        "name": "Piloting",
        "project_default": false,
        "rate": {
            "amount": "100.00",
            "code": "CAD"
        },
        "taskid": 159361,
        "tax1": 2,
        "tax2": 0,
        "tdesc": "Piloting the project based on the expectations of the executive",
        "tname": "Piloting",
        "updated": "2022-02-20 02:20:02",
        "vis_state": 0
    }}';

    public function testTaskFromResponse(): void
    {
        $taskData = json_decode($this->sampleTaskData, true);

        $task = new Task($taskData[Task::RESPONSE_FIELD]);

        $this->assertSame(159361, $task->id);
        $this->assertSame(159361, $task->taskId);
        $this->assertSame(true, $task->billable);
        $this->assertSame('Piloting the project based on the expectations of the executive', $task->description);
        $this->assertSame('Piloting', $task->name);
        $this->assertSame(2, $task->tax1);
        $this->assertSame(0, $task->tax2);
        $this->assertEquals(Decimal::create('100.00'), $task->rate->amount);
        $this->assertSame('CAD', $task->rate->code);
        $this->assertEquals(new DateTime('2022-02-20T07:20:02Z'), $task->updated);
        $this->assertSame(VisState::ACTIVE, $task->visState);
    }

    public function testTaskGetContent(): void
    {
        $taskData = json_decode($this->sampleTaskData, true);
        $task = new Task($taskData['task']);
        $this->assertSame([
            'billable' => true,
            'description' => 'Piloting the project based on the expectations of the executive',
            'name' => 'Piloting',
            'tax1' => 2,
            'tax2' => 0,
            'rate' => [
                'amount' => '100.00',
                'code' => 'CAD'
            ]
        ], $task->getContent());
    }
}
