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
	 * @return Milestone
	 */
	public function addMilestone(Milestone $milestone)
	{
		$milestoneData = $milestone->toArray();

		// create task
		$newMilestoneData = $this->map(
			$this->adapter->create(
				$this->getName(), $milestoneData,  $this->getFieldOrder()
			)
		);

		$newMilestone = new Milestone($newMilestoneData);

		return $newMilestone;
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
		$updatedMilestoneData = $this->map(
			$this->adapter->update(
				$this->getName(), $id, $data, $this->getFieldOrder()
			)
		);

		$updatedMilestone = new Milestone($updatedMilestoneData);

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
		$milestonesData = $this->adapter->read($this->name);
		$milestones = [];
		foreach ($milestonesData as $milestoneData){
			$milestones[] = new Milestone($this->map($milestoneData));
		}
		return $milestones;
	}

	/**
	 * @return array
	 * @inheritdoc
	 */
	protected function getChildClasses()
	{
		return [];
	}

	/**
	 * @return array
	 * @inheritdoc
	 */
	protected function getFieldOrder()
	{
		return ['id','parentId', 'name','reward', 'rewardBudget'];
	}
}


