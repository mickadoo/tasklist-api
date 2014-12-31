<?php
/**
 * Created by PhpStorm.
 * User: mickadoo
 * Date: 31/12/14
 * Time: 13:19
 */

namespace MichaelDevery\Tasklist\Tests;

use MichaelDevery\Tasklist\Models\Task;

class TaskTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param $data
     * @param $shouldSucceed
     * @param null $expectedException
     * @dataProvider hydrateArrayProvider
     */
    public function testHydrateTask($data, $shouldSucceed, $expectedException = null)
    {
        if ($shouldSucceed) {
            $task = new Task($data);
            $taskToArray = json_decode(json_encode($task), true);
            $this->assertTrue($taskToArray == $data);
        } else {
            $this->setExpectedException($expectedException);
            new Task($data);
        }
    }

    public function hydrateArrayProvider(){
        return [
            [
                [
                    "name" => "Test Name 2",
                    "difficulty" => 1,
                    "milestones" => [
                        [
                            "name" => "Test Milestone 1",
                            "reward" => "Reward 1",
                            "rewardBudget" => 10.11
                        ],
                        [
                            "name" => "Test Milestone 2",
                            "reward" => "Reward 2",
                            "rewardBudget" => 5.00
                        ]
                    ]
                ],
                true,
            ],
        ];
    }
}