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
	 * @param Task $task
	 * @return array
	 */
	public function addTask(Task $task)
	{
		$taskData = $this->toArray($task);

        // remove child classes for initialization later
        $childClasses = array();
        foreach ($this->getChildClasses() as $childClass){
			if (isset($taskData[$childClass])) {
				$childClasses[] = $taskData[$childClass];
				unset($taskData[$childClass]);
			}
        }
        // create task
		$newTaskData = $this->map(
            $this->adapter->create(
                $this->getName(), $taskData
            )
        );
        // create child classes
        $parentId = $task->getId();
        foreach ($childClasses as $childClass){
			// todo create child classes using parent id
        }
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
	public function getAllTasks()
	{
		$tasksData = $this->adapter->read($this->name);
        $tasks = array();
        foreach ($tasksData as $taskData){
            $tasks[] = new Task($this->map($taskData));
        }
		return $tasks;
	}

	public function deleteAllTasks()
	{
		$deletedIds = $this->adapter->delete($this->name);
		return $deletedIds;
	}

    /**
     * @param array $data
     * @return array
	 * @description maps a numeric array to string keys
     */
	protected function map(array $data)
	{
		$fields = ['id','name','difficulty'];

		$results = array();
		foreach ($data as $key => $current){
            if (isset($fields[$key])) {
                $results[$fields[$key]] = $current;
            }
		}
		return $results;
	}

	protected function toArray($task)
	{
		return $task->jsonSerialize();
	}

    protected function getChildClasses(){
        return [
            'milestones'
        ];
    }
}


