<?php
namespace MichaelDevery\Tasklist;

class FrontController {

	const CONTROLLER_SUFFIX = 'Controller';
	const CONTROLLER_NAMESPACE = __NAMESPACE__;
	const MODEL_NAMESPACE = __NAMESPACE__;

	/**
	 * @param array $route
	 * @param Request $request
	 * @return string
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

        // check if request is for top level resource
		$parentId = -1;
        $subRequest = ((int) ((count($route) + 1) / 2) > 1) ? true : false;
        if ($subRequest){
            // request targets a sub-resource of parent
            $parent = ucfirst(array_shift($route));
            $parentId = (int) array_shift($route);
        }

        $mainController = null;
        $id = null;
        $action = null;

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
					$id = (int) array_shift($route);
				}
			}
		}

		// create config
		$config = new Config(__DIR__ . '/../config/config.yml');
		// initialize controller and pass request to it
		$controller = new $mainController($request, $config);
        if ($subRequest) {
            $response = $controller->$action($parentId, $id);
        } else {
            $response = $controller->$action($id);
        }
		return $this->returnResponse($response);
	}

	private function returnResponse(Response $response){
		header('Content-type: application/json');
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
		return rtrim($plural,'s');
	}
}
