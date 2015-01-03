<?php
namespace MichaelDevery\Tasklist\Models;

class Milestone extends ChildClass implements \JsonSerializable
{
	/** @var int */
	private $id;

	/** @var string */
	private $name;

	/** @var string */
	private $reward;

	/** @var float */
	private $rewardBudget;

    use HydratableTrait;
    use SerializableTrait;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->hydrate($data);
        }
    }

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getReward()
	{
		return $this->reward;
	}

	/**
	 * @param string $reward
	 */
	public function setReward($reward)
	{
		$this->reward = $reward;
	}

	/**
	 * @return mixed
	 */
	public function getRewardBudget()
	{
		return $this->rewardBudget;
	}

	/**
	 * @param mixed $rewardBudget
	 */
	public function setRewardBudget($rewardBudget)
	{
		$this->rewardBudget = $rewardBudget;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->jsonSerialize();
	}
}