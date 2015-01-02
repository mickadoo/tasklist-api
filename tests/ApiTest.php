<?php
/**
 * Created by PhpStorm.
 * User: mickadoo
 * Date: 31/12/14
 * Time: 13:19
 */

namespace MichaelDevery\Tasklist\Tests;

use MichaelDevery\Tasklist\Models\Milestone;
use MichaelDevery\Tasklist\Models\Task;
use MichaelDevery\TaskList\Response;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @dataProvider badApiRequestDataProvider
     */
    public function testApiExceptions($url, $method = 'GET', $data = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($data){
            $jsonData = json_encode($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData))
            );
        }

        $response = json_decode(curl_exec($curl), true);
        $errorCode = (int) $response['errorCode'];

        $this->assertNotFalse(
            filter_var(
                $errorCode,
                FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => 400,
                        'max_range' => 599
                    )
                )
            )
        );
    }

    /**
     * @param $data
     * @dataProvider taskDataProvider
     */
    public function testAll($data)
    {
        $createdTask = $this->addTask($data);
        $this->updateTask($createdTask->getId());
        $this->getTask($createdTask->getId());
        $this->replaceMilestone($createdTask->getId(), $createdTask->getMilestones()[0]->getId());
        $this->deleteTask($createdTask->getId());
    }

    /**
     * @param $data
     * @dataProvider taskDataProvider
     */
    public function testNestedRequests($data)
    {
        $createdMilestone = $this->nestedPostRequest($data);

        $parentId = $createdMilestone->getParentId();
        $milestoneId = $createdMilestone->getId();

        $this->nestedPatchRequest($parentId, $milestoneId);
        $this->nestedGetAllRequest($parentId);
        $this->nestedDeleteAllRequest($parentId, $milestoneId);

        // cleanup
        $this->deleteTask($parentId);
    }

    /**
     * @param $data
     * @return Milestone
     */
    public function nestedPostRequest($data)
    {
        $milestones = $data['milestones'];
        $milestone = $milestones[0];
        unset($data['milestones']);

        $newTask = $this->addTask($data);
        $parentId = $newTask->getId();

        // add milestones as subrequest
        $jsonData = json_encode($milestone);
        $curl = curl_init('api.tasklist.dev/task/' . $parentId . '/milestone/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData))
        );

        $response = json_decode(curl_exec($curl), true);

        $createdMilestone = new Milestone($response['data'], true);

        $this->assertTrue($parentId ===  $createdMilestone->getParentId());

        return $createdMilestone;
    }

    /**
     * @param int $parentId
     * @param int $milestoneId
     */
    public function nestedPatchRequest($parentId, $milestoneId)
    {
        $updatedData = ['name' => 'Updated Name', 'reward' => 'Updated Reward'];

        $jsonData = json_encode($updatedData);
        $curl = curl_init('api.tasklist.dev/task/' . $parentId . '/milestone/' . $milestoneId . '/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData))
        );

        $response = json_decode(curl_exec($curl), true);

        $updatedMilestone = new Milestone($response['data']);

        $this->assertSame($milestoneId, (int) $updatedMilestone->getId());
        $this->assertTrue($updatedData['name'] ===  $updatedMilestone->getName());
        $this->assertTrue($updatedData['reward'] ===  $updatedMilestone->getReward());
    }

    /**
     * @param int $parentId
     */
    public function nestedGetAllRequest($parentId)
    {
        $curl = curl_init('api.tasklist.dev/task/' . $parentId . '/milestone/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($curl), true);

        $milestonesData = $response['data'];

        foreach ($milestonesData as $milestoneData){
            $milestone = new Milestone($milestoneData);
            $this->assertSame((int) $milestone->getParentId(), $parentId);
        }
    }

    /**
     * @param int $parentId
     */
    public function nestedDeleteAllRequest($parentId, $childId)
    {
        $curl = curl_init('api.tasklist.dev/task/' . $parentId . '/milestone/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($curl), true);

        $deletedIds = $response['data'];

        $this->assertContains($childId, $deletedIds);
    }


    /**
     * @param array $data
     * @return Task
     */
    public function addTask($data)
    {
        $jsonData = json_encode($data);
        $curl = curl_init('api.tasklist.dev/task/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData))
        );

        $response = json_decode(curl_exec($curl), true);

        $createdTask = new Task($response['data'], true);

        $this->assertTrue($response['code'] === 201);
        $this->assertTrue($data['name'] === $createdTask->getName());

        return $createdTask;
    }

    /**
     * @param $id
     */
    public function updateTask($id)
    {
        $updatedData = ['name' => 'New Name'];

        $jsonData = json_encode($updatedData);

        $curl = curl_init('api.tasklist.dev/task/' . $id . '/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData))
        );

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $updatedTask = new Task($response['data'], true);

        $this->assertTrue($response['code'] === 200);
        $this->assertTrue($updatedTask->getName() == $updatedData['name']);
    }

    public function getTask($id)
    {
        $curl = curl_init('api.tasklist.dev/task/' . $id . '/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $updatedTask = new Task($response['data'], true);

        $this->assertTrue($response['code'] === 200);
        $this->assertTrue($updatedTask->getId() == $id);
    }

    public function replaceMilestone($parentId, $milestoneId)
    {
        $updatedData = ['name' => 'Replaced Name'];

        $jsonData = json_encode($updatedData);

        $curl = curl_init('api.tasklist.dev/milestone/' . $parentId . '/milestone/' . $milestoneId . '/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData))
        );

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $updatedMilestone = new Milestone($response['data'], true);

        $this->assertTrue($response['code'] === 200);
        $this->assertTrue($updatedMilestone->getName() == $updatedData['name']);
        $this->assertEmpty($updatedMilestone->getReward());
    }

    public function testGetAllTasks()
    {
        $curl = curl_init('api.tasklist.dev/task/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $tasksData = $response['data'];

        // test all data to be valid
        $tasks = [];
        foreach ($tasksData as $taskData){
            $testTask = new Task($taskData);
            if ($testTask) {
                $tasks[] = $testTask;
            }
        }
        $this->assertTrue(count($tasks) === count($tasksData));
    }

    public function deleteTask($id){
        $curl = curl_init('api.tasklist.dev/task/' . $id . '/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $this->assertTrue($response['code'] === 200);
        $this->assertTrue($response['data']['id'] === $id);
    }

    /**
     * @return array
     */
    public function taskDataProvider(){
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
            ]
        ];
    }

    /**
     * @return array
     */
    public function badApiRequestDataProvider(){
        return [
            [
                'api.tasklist.dev/'
            ],
            [
                'api.tasklist.dev/tasks',
            ],
            [
                'api.tasklist.dev/task/cat'
            ],
            [
                'api.tasklist.dev/task/10/milestone/1'
            ],
            [
                'api.tasklist.dev/task/',
                'HORSE'
            ],
            [
                'api.tasklist.dev/task/',
                'POST',
                [
                    'nan' => 'Cat'
                ]
            ],
            [
                'api.tasklist.dev/task/',
                'POST',
                [
                    'name' => 'Test Name',
                    'difficulty' => 51
                ]
            ]
        ];
    }
}