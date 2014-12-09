<?php
namespace MichaelDevery\TaskList\Library;

use MichaelDevery\Tasklist\Request;
use MichaelDevery\Tasklist\Library\AbstractModel;
use MichaelDevery\Tasklist\Config;

abstract class AbstractController
{
	/** @var Request */
	protected $request;
	/** @var AbstractModel */
	protected $model;
	/** @var Config */
	protected $config;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, Config $config)
	{
		$this->request = $request;
		$this->config = $config;
		$ownName = str_replace('Controller', '', (new \ReflectionClass($this))->getShortName());
		$modelName =  MODEL_NAMESPACE . '\\' .  $ownName . 'Model';
		$this->model = new $modelName($modelName, $config);
	}
}

