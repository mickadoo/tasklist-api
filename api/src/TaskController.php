<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController;
use MichaelDevery\Tasklist\Library\ApiException;
use MichaelDevery\Tasklist\Models\Task;

class TaskController extends AbstractController
{
    /**
     * @param int $id
     * @return Response
     * @throws ApiException
     */
	public function getTask($id)
    {
        $task = $this->model->getTask($id);
        $response = new Response();
        $response->setCode(200);
        $response->setData(json_encode($task));
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $task->getId());
        return $response;
    }

	/**
	 * @return Response
	 */
	public function addTask()
	{
		$data = $this->request->getData();
        $task = new Task($data);
		$newTask = $this->model->addTask($task);

        $response = new Response();
        $response->setCode(201);
        $response->setData(json_encode($newTask));
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $newTask->getId());
        return $response;
    }

	/**
	 * @param int $id
	 * @return Response
	 */
	public function deleteTask($id)
	{
		$deletedId = $this->model->deleteTask($id);
        $response = new Response();
        $response->setCode(200);
        $response->setData(['id' => $deletedId]);
		return $response;
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function updateTask($id)
	{
		$data = $this->request->getData();
        $updatedTask = $this->model->updateTask($id, $data);

        $response = new Response();
        $response->setCode(200);
        $response->setData(json_encode($updatedTask));
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $updatedTask->getId());
        return $response;
	}

    public function getAllTasks()
    {
        $tasks = $this->model->getAllTasks();

        // have to call on each one
        foreach ($tasks as $key => $task){
            $tasks[$key] = json_encode($task);
        }

        $response = new Response();
        $response->setCode(200);

        $response->setData(json_encode($tasks));
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/');

        return $response;
    }

    public function deleteAllTasks()
    {
        $deletedIds = $this->model->deleteAllTasks();

        $response = new Response();
        $response->setCode(200);

        $response->setData(json_encode($deletedIds));

        return $response;
    }
}
