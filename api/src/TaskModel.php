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
        // todo error if not found
		$task = new Task($taskData);
		return $task;
	}

	/**
	 * @param array
	 * @return array
	 */
	public function addTask($data)
	{
		$newTaskData = $this->map(
            $this->adapter->create(
                $this->getName(), $data
            )
        );
        $task = new Task($newTaskData);
		return $task;
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
		$updatedTaskData = $this->map(
            $this->adapter->update(
                $this->getName(), $id, $data
            )
        );

        $updatedTask = new Task($updatedTaskData);

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
		$tasksData = $this->adapter->read($this->name);
        $tasks = array();
        foreach ($tasksData as $taskData){
            $tasks[] = new Task($this->map($taskData));
        }
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


