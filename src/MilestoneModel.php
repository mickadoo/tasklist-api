<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Library\ApiException;
use MichaelDevery\Tasklist\Models\Milestone;

Class MilestoneModel extends AbstractModel
{
	/**
	 * @param int $id
	 * @param int $parentId
	 * @return Milestone
	 * @throws ApiException
	 */
	public function getMilestone($id, $parentId)
	{
		$milestoneData = $this->map($this->adapter->read($this->name, $id));
		$milestone = new Milestone($milestoneData);
		if ($parentId && $milestone->getParentId() !== $parentId){
			throw new ApiException(400, "No milestone with Id $parentId exists for task $id");
		}
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

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function replaceMilestone($id, $data)
	{
		$replacedMilestoneData = $this->map(
			$this->adapter->replace(
				$this->getName(), $id, $data, $this->getFieldOrder()
			)
		);

		$replacedMilestone = new Milestone($replacedMilestoneData);

		return $replacedMilestone;
	}


	/**
	 * @param int $parentId
	 * @return Milestone[]
	 */
	public function getAllMilestones($parentId = null)
	{
		$milestonesData = $this->adapter->read($this->name);
		$milestones = [];
		foreach ($milestonesData as $milestoneData){
			if ($parentId){
				$newMilestone = new Milestone($this->map($milestoneData));
				if ($newMilestone->getParentId() == $parentId){
					$milestones[] = $newMilestone;
				}
			} else {
				$milestones[] = new Milestone($this->map($milestoneData));
			}
		}
		return $milestones;
	}

	/**
	 * @param int $parentId
	 * @return array
	 */
	public function deleteAllMilestones($parentId = null)
	{
		$deletedIds = [];

		if (!$parentId){
			// delete them all
			$deletedIds = $this->adapter->delete($this->name);
		} else {
			// delete only those matching parent id
			$allChildMilestones = $this->getAllMilestones($parentId);
			foreach ($allChildMilestones as $childMilestone){
				$childId = $childMilestone->getId();
				$deletedIds[] = $this->deleteMilestone($childId);
			}
		}
		return $deletedIds;
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
		return ['id','parentId', 'name','reward', 'rewardBudget', 'complete'];
	}
}
