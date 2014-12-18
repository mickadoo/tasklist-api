<?php
namespace MichaelDevery\Tasklist;

require_once('../vendor/autoload.php');

define('MODEL_NAMESPACE', __NAMESPACE__);

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

// set query parameters
$queryParams = (isset($urlParts['query']) ? $urlParts['query'] : null);

// build request
$request = new Request($url, $method, $data, $queryParams);

$frontController = new FrontController();

// route
var_dump($frontController->route($route, $request));
