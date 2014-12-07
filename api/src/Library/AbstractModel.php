<?php
namespace MichaelDevery\Tasklist\Library;

use AdapterInterface;

abstract class AbstractModel
{
	/** @var AdapterInterface */
	protected $adapter;
	/** @var string */
	protected $name;

	/**
	 * @param string $name;
	 */
	public function __construct($name)
	{
		// todo create Adapter using config file
		$this->name = $name;
	}

}
