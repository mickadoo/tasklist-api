<?php
namespace MichaelDevery\Tasklist\Models;

use MichaelDevery\Tasklist\Library\ApiException;

class Task implements \JsonSerializable
{
	use HydratableTrait;
	use SerializableTrait;

	/** @var int */
	private $id;

	/** @var string */
	private $name;

	/** @var int */
	private $difficulty;

	/** @var Milestone[] */
	private $milestones;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = [])
	{
		if ($data) {
			$this->hydrate($data, array('milestones'));
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
	 * @param int $difficulty
	 * @throws ApiException
	 */
	public function setDifficulty($difficulty)
	{
		$difficulty = (int)$difficulty;
		if ($difficulty > 5 || $difficulty < 1) {
			throw new ApiException(400, "Task difficulty must be in the range 1 - 5");
		}
		$this->difficulty = $difficulty;
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

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->jsonSerialize();
	}
}