<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\ApiException;

class FrontController {

	const CONTROLLER_SUFFIX = 'Controller';
	const CONTROLLER_NAMESPACE = __NAMESPACE__;
	const MODEL_NAMESPACE = __NAMESPACE__;

	/**
	 * @param array $route
	 * @param Request $request
	 * @return string
	 * @throws ApiException
	 * @description Initializes controller and calls action in it
	 */
	function route ($route, $request) {

		$acceptedMethods = ['GET','POST','PUT','PATCH','DELETE'];
		$error = false;

		// if request targets base url return error
		if ($route[0] === '') {
			throw new ApiException(400, 'Please specify a resource in the url.');
		}
		
		// refuse request if method not recognized
		if (!in_array($request->getMethod(), $acceptedMethods)) {
			throw new ApiException(405, "Method " . $request->getMethod() . " is not allowed");
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

        // check if request is for top level resource
		$parentId = -1;
        $subRequest = ((int) ((count($route) + 1) / 2) > 1) ? true : false;
        if ($subRequest){
            // request targets a sub-resource of parent
           	// remove parent resource from url (e.g. task)
			array_shift($route);
			// get parent id
            $parentId = (int) array_shift($route);
        }

        $mainController = null;
        $id = null;
        $action = null;
		$resource = null;

		if ($error === false) {
			// specify resource
			$resource = ucfirst(array_shift($route));
			// append suffix to controller name to follow naming convention
			$mainController = $this::CONTROLLER_NAMESPACE . '\\' . trim($resource . $this::CONTROLLER_SUFFIX);
			// define action
			if ($request->getMethod() === Request::METHOD_POST && $requestSpecificity == REQUEST_TARGET_ALL){
				$action = $actionPrefix[Request::METHOD_POST] . ucfirst($resource);
			} else {
				$action =  $actionPrefix[$request->getMethod()] . $requestSpecificity;
				// add the name of the resource to action
				if ($requestSpecificity === REQUEST_TARGET_ALL){
					$action .= ucfirst($this->pluralize($resource));
				} else {
					$action .= ucfirst($resource);
					$id = array_shift($route);
					if (!is_numeric($id)){
						throw new ApiException(400, 'Id must be a number');
					} else {
						$id = (int)$id;
					}
				}
			}
		}

		// create config
		$config = new Config(__DIR__ . '/../config/config.yml');
		// initialize controller and pass request to it
		if (class_exists($mainController)) {
			$controller = new $mainController($request, $config);
		} else {
			throw new ApiException('400', 'Controller does not exist for ' . $resource);
		}
        if ($subRequest) {
            $response = $controller->$action($id, $parentId);
        } else {
            $response = $controller->$action($id);
        }
		return $this->returnResponse($response);
	}

	private function returnResponse(Response $response){
		header('Content-type: application/json');
		header("Access-Control-Allow-Origin: *");
		http_response_code($response->getCode());
		return json_encode($response);
	}

	/**
	 * @param string $word
	 * @return string
	 */
	static function pluralize($word)
	{
		// could expand on this, maybe use an existing solution
		return $word . 's';
	}

	/**
	 * @param string $plural
	 * @return string
	 */
	static function singularize($plural)
	{
		return rtrim($plural, 's');
	}

	/**
	 * @param string $jsonString
	 * @return boolean
	 */
	public static function isValidJson($jsonString)
	{
		json_decode($jsonString);
		return (json_last_error() === JSON_ERROR_NONE) ? true : false;
	}
}
