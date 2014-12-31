<?php
namespace MichaelDevery\Tasklist;

use Symfony\Component\Yaml\Yaml;

class Config
{
	/** @var string */
	private $adapter;
	/** @var array */
	private $adapterSettings;

	/**
	 * @param array $data
	 */
	public function __construct($dir)
	{
		$data = $this->readConfig($dir);
		$this->adapter = (isset($data['adapter'])) ? $data['adapter'] : null;
		$this->adapterSettings = (isset($data['adapters'][$this->adapter])) ? $data['adapters'][$this->adapter] : null;
	}

	/**
	 * @return string
	 */
	public function getAdapterName()
	{
		return $this->adapter;
	}

	/**
	 * @return array
	 */
	public function getAdapterSettings()
	{
		return $this->adapterSettings;
	}

	/**
	 * @return array
	 */
	public function readConfig($dir)
	{
		if(file_exists($dir)){
			return Yaml::parse($dir);
		} else {
			throw new \Exception('Cannot find config file');
		}
	}
}
