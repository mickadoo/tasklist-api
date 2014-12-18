<?php
namespace MichaelDevery\Tasklist\Models;

class Reward implements JsonSerializableInterface
{
	/** @var int */
	private $id;

	/** @var int */
	private $milestoneId;

	/** @var string */
	private $name;

	/** @var float */
	private $budget;

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
	public function getMilestoneId()
	{
		return $this->milestoneId;
	}

	/**
	 * @param int $milestoneId
	 */
	public function setMilestoneId($milestoneId)
	{
		$this->milestoneId = $milestoneId;
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
	 * @return float
	 */
	public function getBudget()
	{
		return $this->budget;
	}

	/**
	 * @param float $budget
	 */
	public function setBudget($budget)
	{
		$this->budget = $budget;
	}
}
