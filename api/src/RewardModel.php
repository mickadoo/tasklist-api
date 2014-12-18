<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Models\Reward;

Class RewardModel extends AbstractModel
{
    /**
     * @param $id
     * @return Reward
     */
	public function getReward($id)
	{
		$rewardData = $this->map($this->adapter->read($this->name, $id));
		$reward = new Reward($rewardData);
		return json_encode($reward->toArray());
	}

	/**
	 * @param array
	 * @return array
	 */
	public function addReward($data)
	{
		$newReward = $this->adapter->create($this->getName(), $data);
		return $newReward;
	}

	/**
	 * @param int $id
	 * @return int
	 */
	public function deleteReward($id)
	{
		$deletedId = $this->adapter->delete($this->getName(), $id);
		return $deletedId;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function updateReward($id, $data)
	{
		$updatedReward = $this->adapter->update($this->getName(), $id, $data);
		return $updatedReward;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public function replaceReward($id, $data)
	{
		$updatedReward = $this->adapter->update($this->getName(), $id, $data);
		return $updatedReward;
	}

	/**
	 * @return Reward[]
	 */
	public function getAllRewards(){
		$Rewards = $this->adapter->read($this->name);
		//todo build Reward object and return it
		return $Rewards;
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

	//todo add all other methods	
}


