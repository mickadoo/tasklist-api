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
        $response->setData($task);
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $task->getId());
        return $response;
    }

    /**
     * @return Response
     * @throws ApiException
     */
	public function addTask()
	{
		$data = $this->request->getData();
        if (!$data){
            throw new ApiException(400, 'No data provided for new task');
        }
        $task = new Task($data);
		$newTask = $this->model->addTask($task);

        $response = new Response();
        $response->setCode(201);
        $response->setData($newTask);
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
        $response->setData($updatedTask);
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $updatedTask->getId());
        return $response;
	}

    /**
     * @param int $id
     * @return Response
     */
    public function replaceTask($id)
    {
        $data = $this->request->getData();
        $replacedTask = $this->model->replaceTask($id, $data);

        $response = new Response();
        $response->setCode(200);
        $response->setData($replacedTask);
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $replacedTask->getId());
        return $response;
    }

    public function getAllTasks()
    {
        $tasks = $this->model->getAllTasks();

        // have to call on each one
        foreach ($tasks as $key => $task){
            $tasks[$key] = $task;
        }

        $response = new Response();
        $response->setCode(200);

        $response->setData($tasks);
        $response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/');

        return $response;
    }

    public function deleteAllTasks()
    {
        $deletedIds = $this->model->deleteAllTasks();

        $response = new Response();
        $response->setCode(200);

        $response->setData($deletedIds);

        return $response;
    }
}
