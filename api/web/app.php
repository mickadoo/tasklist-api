<?php
namespace MichaelDevery\Tasklist;

require_once('../autoloader.php');

const REQUEST_TARGET_SINGLE = '';
const REQUEST_TARGET_ALL = 'All';

// get url
$url = trim($_SERVER['REQUEST_URI'],'/');

// get method and route
$method = $_SERVER['REQUEST_METHOD'];

// get json data from request
$data = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : null;

// parse url
$urlParts = parse_url($url);

if (isset($urlParts['path'])){
	$route = explode('/', trim($urlParts['path'], '/'));
} else {
	$route = [''];
}

// set query parameters
$queryParams = (isset($urlParts['query']) ? $urlParts['query'] : null);

// build request
$request = new Request($url, $method, $data, $queryParams);

// route
route($route, $request);

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
	
	if ($error === false) {
		$mainController = array_shift($route);
		// define action
		$action =  $actionPrefix[$request->getMethod()] . $requestSpecificity;
		// add the name of the resource to action
		if ($requestSpecificity === REQUEST_TARGET_ALL){
			$action .= ucfirst(pluralize($mainController));
		} else {
			$action .= ucfirst($mainController);
		}
	}
	
	// todo initialize controller and pass request to it

	echo "Route : $mainController:$action \n";
	echo "Main Controller : $mainController Query : " . $request->getQueryParams() . " . Method : " . $request->getMethod() . "\n";
	echo "Specificity : $requestSpecificity \n";

	exit;
}

function pluralize($word)
{
	// could expand on this, maybe use an existing solution
	return $word . 's';
}
