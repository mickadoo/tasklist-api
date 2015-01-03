<?php
namespace MichaelDevery\Tasklist;
require_once('../vendor/autoload.php');

use MichaelDevery\Tasklist\Library\ApiException;

const REQUEST_TARGET_SINGLE = '';
const REQUEST_TARGET_ALL = 'All';

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
	http_response_code($response->getErrorCode());
}

// set query parameters
$queryParams = (isset($urlParts['query']) ? $urlParts['query'] : null);

// build request
$request = new Request($url, $method, $data, $queryParams);

$frontController = new FrontController();

set_exception_handler('MichaelDevery\Tasklist\apiExceptionHandler');

// route
echo $frontController->route($route, $request);