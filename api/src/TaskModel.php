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
        // remove child classes for initialization later
        $childClasses = arrays();
        foreach ($this->getChildClasses() as $childClass){
            if (isset($data[$childClass])) {
                $childClasses[] = $data[$childClass];
                unset($data[$childClass]);
            }
        }
        // create task
		$newTaskData = $this->map(
            $this->adapter->create(
                $this->getName(), $data
            )
        );
        // create child classes
        $parentId = $task->getId();
        foreach ($childClasses as $childClass){

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

    protected function getChildClasses(){
        return [
            'reward',
            'milestone'
        ];
    }

	//todo add all other methods	
}


