<?php
/**
 * Created by PhpStorm.
 * User: mickadoo
 * Date: 31/12/14
 * Time: 13:19
 */

namespace MichaelDevery\Tasklist\Tests;

use MichaelDevery\Tasklist\Models\Task;
use MichaelDevery\TaskList\Response;

class ApiTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param $data
     * @dataProvider taskDataProvider
     */
    public function testAll($data)
    {
        $createdId = $this->addTask($data);
        $this->updateTask($createdId);
        $this->getTask($createdId);
        $this->deleteTask($createdId);
    }


    /**
     * @param array $data
     * @return int
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

        $createdTask = new Task(json_decode($response['data'], true));

        $this->assertTrue($response['code'] === 201);
        $this->assertTrue($data['name'] === $createdTask->getName());

        return $createdTask->getId();
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

        $updatedTask = new Task(json_decode($response['data'], true));

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

        $updatedTask = new Task(json_decode($response['data'], true));

        $this->assertTrue($response['code'] === 200);
        $this->assertTrue($updatedTask->getId() == $id);
    }

    public function testGetAllTasks()
    {
        $curl = curl_init('api.tasklist.dev/task/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        /** @var Response $response */
        $response = json_decode(curl_exec($curl), true);

        $tasksData = json_decode($response['data'], true);

        // test all data to be valid
        $tasks = [];
        foreach ($tasksData as $taskData){
            $testTask = new Task(json_decode($taskData, true));
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
}