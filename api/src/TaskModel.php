<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Models\ChildClass;
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
	 * @return Task
	 */
	public function addTask(Task $task)
	{
		$taskData = $task->toArray();

        // remove child classes for initialization later
        $childClasses = array();
        foreach ($this->getChildClasses() as $childClass){
			if (isset($taskData[$childClass])) {
				$childClasses[$childClass] = $taskData[$childClass];
				unset($taskData[$childClass]);
			}
        }

        // create task
		$newTaskData = $this->map(
            $this->adapter->create(
                $this->getName(), $taskData, $this->getFieldOrder()
            )
        );

        // create child classes
        $parentId = $newTaskData['id'];
        foreach ($childClasses as $name => $childClassGroup){
			foreach ($childClassGroup as $childClass) {
				/** @var ChildClass $childClass */
				$childClass->setParentId($parentId);
				$modelName = ucfirst(FrontController::singularize($name));
				$childModelName = __NAMESPACE__ . '\\' . $modelName . 'Model';
				$childModel = new $childModelName($modelName, $this->config);
				$addMethod = 'add' . $modelName;
				$childModel->$addMethod($childClass);
			}
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
		$fields = $this->getFieldOrder();

		$results = array();
		foreach ($data as $key => $current){
            if (isset($fields[$key])) {
                $results[$fields[$key]] = $current;
            }
		}
		return $results;
	}

	/**
	 * @return array
	 * @inheritdoc
	 */
    protected function getChildClasses(){
        return [
            'milestones'
        ];
    }

	/**
	 * @return array
	 * @inheritdoc
	 */
	protected function getFieldOrder()
	{
		return ['id','name','difficulty'];
	}
}


