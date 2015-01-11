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
	/** @var Config */
	protected $config;

	/**
	 * @param string $name
	 * @param Config $config
	 */
	public function __construct($name, Config $config)
	{
		$this->name = $name;
		$this->config = $config;
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

	/**
	 * @return array
	 * @description Used by the hydrate function, so it won't try to hydrate objects directly, but instead
	 * create them separately.
	 */
    protected abstract function getChildClasses();

	/**
	 * @return array
	 * @description required for non-database storage to specify which order to store the fields in
	 */
	protected abstract function getFieldOrder();

	/**
	 * @param array $data
	 * @return array
	 * @description used when returning non-associative arrays from storage adapter. maps the numeric key array to
	 * an associative one
	 */
	protected function map(array $data)
	{
		$fields = $this->getFieldOrder();

		$results = array();
		foreach ($data as $key => $current){
			if (isset($fields[$key])) {
				$results[$fields[$key]] = $current;
			}
		}
		return $results;
	}
}
