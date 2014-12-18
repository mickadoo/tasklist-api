<?php
namespace MichaelDevery\Tasklist\Models;

class Milestone implements \JsonSerializable
{
	/** @var int */
	private $id;

	/** @var int */
	private $taskId;

	/** @var string */
	private $name;

	/** @var Reward */
	private $reward;

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
	 * @return int
	 */
	public function getTaskId()
	{
		return $this->taskId;
	}

	/**
	 * @param int $taskId
	 */
	public function setTaskId($taskId)
	{
		$this->taskId = $taskId;
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
	 * @return Reward
	 */
	public function getReward()
	{
		return $this->reward;
	}

	/**
	 * @param Reward $reward
	 */
	public function setReward($reward)
	{
		$this->reward = $reward;
	}
}
