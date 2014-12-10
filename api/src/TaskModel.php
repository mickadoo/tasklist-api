<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;

Class TaskModel extends AbstractModel
{
	/**
	 * @param int $id
	 */
	public function getTask($id)
	{
		$taskData = $this->adapter->read($this->name, $id);
		//todo build Task object and return it;
		return $taskData;
	}

	/**
	 * @param array
	 * @return array
	 */
	public function addTask($data)
	{
		$newTask = $this->adapter->create($this->getName(), $data);
		return $newTask;
	}

	/**
	 * @param int $id
	 * @return int
	 */
	public function deleteTask($id)
	{
		$deletedId = $this->adapter->delete($this->getName(), $id);
		return $deletedId;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function updateTask($id, $data)
	{
		$updatedTask = $this->adapter->update($this->getName(), $id, $data);
		return $updatedTask;
	}

	//todo add all other methods	
}


