<?php
namespace MichaelDevery\TaskList\Library;

use MichaelDevery\Tasklist\Request;

abstract class AbstractController
{
	/** @var Request */
	protected $request;
	/** todo what is the model? */
	protected $model;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}
}
