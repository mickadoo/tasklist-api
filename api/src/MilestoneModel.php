<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Models\Milestone;

Class MilestoneModel extends AbstractModel
{
    /**
     * @param $id
     * @return Milestone
     */
	public function getMilestone($id)
	{
		$milestoneData = $this->map($this->adapter->read($this->name, $id));
		$milestone = new Milestone($milestoneData);
		return $milestone;
	}

	/**
	 * @param Milestone $milestone
	 * return Milestone
	 */
	public function addMilestone(Milestone $milestone)
	{
		$milestoneData = $milestone->toArray();

		// create task
		$newTaskData = $this->map(
			$this->adapter->create(
				$this->getName(), $milestoneData,  $this->getFieldOrder()
			)
		);
	}

	/**
	 * @param int $id
	 * @return int
	 */
	public function deleteMilestone($id)
	{
		$deletedId = $this->adapter->delete($this->getName(), $id);
		return $deletedId;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function updateMilestone($id, $data)
	{
		$updatedMilestone = $this->adapter->update($this->getName(), $id, $data);
		return $updatedMilestone;
	}

	public function deleteAllMilestones()
	{
		$deletedIds = $this->adapter->delete($this->name);
		return $deletedIds;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function replaceMilestone($id, $data)
	{
		$updatedMilestone = $this->adapter->update($this->getName(), $id, $data);
		return $updatedMilestone;
	}

	/**
	 * @return Milestone[]
	 */
	public function getAllMilestones(){
		$Milestones = $this->adapter->read($this->name);
		//todo build Milestone object and return it
		return $Milestones;
	}

    /**
     * @param array $data
     * @return array
     */
	protected function map(array $data)
	{
		$fields = ['id','milestoneId','name','budget'];

		$results = array();
		foreach ($data as $key => $current){
            if (isset($fields[$key])) {
                $results[$fields[$key]] = $current;
            }
		}
		return $results;
	}

	protected function toArray(Milestone $milestone)
	{
		return [
			'id' => $milestone->getId(),
			'parentId' => $milestone->getParentId(),
			'name' =>  $milestone->getName(),
			'reward' => $milestone->getReward(),
			'rewardBudget' => $milestone->getRewardBudget()
		];
	}

	protected function getChildClasses()
	{
		return [];
	}

	protected function getFieldOrder()
	{
		return ['id','parentId', 'name','reward', 'rewardBudget'];
	}

	//todo add all other methods	
}


