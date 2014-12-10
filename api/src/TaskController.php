<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController; 

class TaskController extends AbstractController
{
	/**
	 * @return Response
	 */
	public function getTask($id)
	{
		$taskData = $this->model->getTask($id);
		die(var_dump($taskData));
		// todo make proper response
		return new Response();
	}	

	/**
	 * @return Response
	 */
	public function addTask()
	{
		$data = $this->request->getData();
		$newTask = $this->model->addTask($data);
		die(var_dump($newTask));
		// todo make proper response format
		return new Response();

	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function deleteTask($id)
	{
		$deletedId = $this->model->deleteTask($id);
		die('Deleted : ' . $deletedId);
		// todo make proper response
		return new Response();
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function updateTask($id)
	{
		$data = $this->request->getData();
		$updatedTask = $this->model->updateTask($id, $data);
		die('Updated: \n ' . var_dump($updatedTask));
		// todo make proper response
		return new Response();
	}
}
