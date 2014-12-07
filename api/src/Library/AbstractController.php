<?php
namespace MichaelDevery\TaskList\Library;

use MichaelDevery\Tasklist\Request;
use MichaelDevery\Tasklist\Library\AbstractModel;

abstract class AbstractController
{
	/** @var Request */
	protected $request;
	/** @var AbstractModel */
	protected $model;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
		$ownName = str_replace('Controller', '', (new \ReflectionClass($this))->getShortName());
		$modelName =  MODEL_NAMESPACE . '\\' .  $ownName . 'Model';

		$this->model = new $modelName($modelName);
	}
}
