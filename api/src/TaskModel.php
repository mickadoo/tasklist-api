<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Models\Task;

Class TaskModel extends AbstractModel
{
    /**
     * @param $id
     * @return Task
     */
	public function getTask($id)
	{
		$taskData = $this->map($this->adapter->read($this->name, $id));
		$task = new Task($taskData);
		return json_encode($task->toArray());
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

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function replaceTask($id, $data)
	{
		$updatedTask = $this->adapter->update($this->getName(), $id, $data);
		return $updatedTask;
	}

	/**
	 * @return Task[]
	 */
	public function getAllTasks(){
		$tasks = $this->adapter->read($this->name);
		//todo build task object and return it
		return $tasks;
	}

    /**
     * @param array $data
     * @return array
     */
	protected function map(array $data)
	{
		$fields = ['id','name','difficulty','goal'];

		$results = array();
		foreach ($data as $key => $current){
            if (isset($fields[$key])) {
                $results[$fields[$key]] = $current;
            }
		}
		return $results;
	}

	//todo add all other methods	
}


