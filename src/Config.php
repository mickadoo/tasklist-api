<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\ApiException;
use Symfony\Component\Yaml\Yaml;

class Config
{
	/** @var string */
	private $adapter;
	/** @var array */
	private $adapterSettings;
	/** @var  array */
	private $customRoutes;

	/**
	 * @param string $dir
	 * @throws ApiException
	 */
	public function __construct($dir)
	{
		$data = $this->readConfig($dir);
		$this->adapter = (isset($data['adapter'])) ? $data['adapter'] : null;
		$this->adapterSettings = (isset($data['adapters'][$this->adapter])) ? $data['adapters'][$this->adapter] : [];
		$this->customRoutes = (isset($data['custom_routes'])) ? $data['custom_routes'] : [];
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
	public function getCustomRoutes()
	{
		return $this->customRoutes;
	}

	/**
	 * @param $dir
	 * @return array
	 * @throws \Exception
	 */
	public function readConfig($dir)
	{
		$configFile = $dir . 'config.yml';
		$routesFile = $dir. 'routes.yml';

		if(file_exists($configFile)){
			$config =  Yaml::parse($configFile);
		} else {
			throw new ApiException(500, 'Cannot find config file');
		}
		// load routes
		if(file_exists($routesFile)){
			$config['custom_routes'] =  Yaml::parse($routesFile);
		}
		return $config;
	}
}
