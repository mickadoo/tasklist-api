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

$mainController = new Controller();

// route
$mainController->route($route, $request);