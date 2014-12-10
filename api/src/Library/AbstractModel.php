<?php
namespace MichaelDevery\Tasklist\Library;

use MichaelDevery\Tasklist\Library\Adapters\AdapterInterface;
use MichaelDevery\Tasklist\Config;

abstract class AbstractModel
{
	/** @var AdapterInterface */
	protected $adapter;
	/** @var string */
	protected $name;

	/**
	 * @param string $name
	 * @param Config $config
	 */
	public function __construct($name, Config $config)
	{
		$this->name = $name;
		$adapter = $config->getAdapterName();
		$adapter = __NAMESPACE__ . '\\Adapters\\' . $adapter;
		$this->adapter = new $adapter();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}
