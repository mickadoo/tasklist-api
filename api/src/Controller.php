<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\TaskController;

class Controller {


	const CONTROLLER_SUFFIX = 'Controller';
	const CONTROLLER_NAMESPACE = __NAMESPACE__;

	/**
	 * @param array $route
	 * @param Request $request
	 * @description Initializes controller and calls action in it
	 */
	function route ($route, $request) {

		$acceptedMethods = ['GET','POST','PUT','PATCH','DELETE'];
		$error = false;

		// if request targets base url return error
		if ($route[0] === '') {
			exit("cannot target base url \n");
			$error = true;
			// $route = array('Error', 'TargetBase');
			exit;
		}
		
		// refuse request if method not recognized
		if (!in_array($request->getMethod(), $acceptedMethods)) {
			exit("method not allowed \n");
			$error = true;
			// $route = array('Error','BadMethod'); 
			exit;
		}

		$actionPrefix = array(
			Request::METHOD_POST => 'add',
			Request::METHOD_GET => 'get',
			Request::METHOD_PATCH => 'update',
			Request::METHOD_PUT => 'replace',
			Request::METHOD_DELETE => 'delete'
		);
		
		// get request specificity to prefix actions
		$requestSpecificity = count($route) % 2 === 0 ? REQUEST_TARGET_SINGLE : REQUEST_TARGET_ALL;
		$id = null;

		if ($error === false) {
			// specify resource
			$resource = ucfirst(array_shift($route));
			// append suffix to controller name to follow naming convention
			$mainController = $this::CONTROLLER_NAMESPACE . '\\' . trim($resource . $this::CONTROLLER_SUFFIX);
			// define action
			$action =  $actionPrefix[$request->getMethod()] . $requestSpecificity;
			// add the name of the resource to action
			if ($requestSpecificity === REQUEST_TARGET_ALL){
				$action .= ucfirst($this->pluralize($resource));
			} else {
				$action .= ucfirst($resource);
				$id = (int) array_shift($route);
			}
		}

		// initialize controller and pass request to it
		$controller = new $mainController($request);
		die($controller->$action($id) . "\n");
		return $controller->$action($id);
	}

	function pluralize($word)
	{
		// could expand on this, maybe use an existing solution
		return $word . 's';
	}
}
