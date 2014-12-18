<?php
namespace MichaelDevery\Tasklist\Models;

class Task implements JsonSerializableInterface
{
	/** @var int */
	private $id;

	/** @var string */
	private $name;

	/** @var int */
	private $difficulty;

	/** @var string */
	private $goal;

	/** @var Milestone[] */
	private $milestones;

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
	 * @var int $id
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
	 * @var string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}


	/**
	 * @return int
	 */
	public function getDifficulty()
	{
		return $this->difficulty;
	}

	/**
	 * @var int $difficulty
	 */
	public function setDifficulty($difficulty)
	{
		$this->difficulty = $difficulty;
	}


	/**
	 * @return string
	 */
	public function getGoal()
	{
		return $this->goal;
	}

	/**
	 * @var string $goal
	 */
	public function setGoal($goal)
	{
		$this->goal = $goal;
	}

	/**
	 * @return Milestone[]
	 */
	public function getMilestones()
	{
		return $this->milestones;
	}

	/**
	 * @var Milestone[] $milestones
	 */
	public function setMilestones($milestones)
	{
		$this->milestones = $milestones;
	}

	/**
	 * @var Milestone $milestone
	 */
	public function setMilestone($milestone)
	{
		$this->milestones[] = $milestone;
	}
}