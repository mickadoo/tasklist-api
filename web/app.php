<?php
namespace MichaelDevery\Tasklist;
require_once('../vendor/autoload.php');

use MichaelDevery\Tasklist\Library\ApiException;

const REQUEST_TARGET_SINGLE = '';
const REQUEST_TARGET_ALL = 'All';

/**
 * @param ApiException $apiException
 */
function apiExceptionHandler(ApiException $apiException)
{
	$response = new ErrorResponse();
	$response->setErrorCode($apiException->getErrorNum());
	$response->setErrorMessage($apiException->getErrorMessage());
	header('Content-type: application/json');
	header('Error-message: ' . $response->getErrorMessage());
	echo json_encode($response);
	http_response_code($response->getErrorCode());
}

// get url
$url = trim($_SERVER['REQUEST_URI'],'/');

// get method and route
$method = $_SERVER['REQUEST_METHOD'];

// get json data from request
$data = file_get_contents('php://input');

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
$request = new Request($urlParts['path'], $method, $data, $queryParams);

$frontController = new FrontController();

set_exception_handler('MichaelDevery\Tasklist\apiExceptionHandler');

// route
echo $frontController->route($route, $request);