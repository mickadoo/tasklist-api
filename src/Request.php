<?php
namespace MichaelDevery\Tasklist;

class Request
{

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PATCH = 'PATCH';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';

	/** @var string */
	private $url;
	/** @var string */
	private $method;
	/** @var string */
	private $data;
	/** @var string */
	private $queryParams;
	
	/**
	 * @param string $url
	 * @param string $method
	 * @param string $data
	 */
	public function __construct($url, $method = Request::METHOD_GET, $data = null, $queryParams = null)
	{	
		$this->url = rtrim($url,'/');
		$this->method = $method;
		if ($this->isValidJson($data)) {
			$this->data = json_decode($data, 1);
		}
		$this->queryParams = $queryParams;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @return string
	 */
	public function getQueryParams()
	{
		return $this->queryParams;
	}

	/**
	 * @param string $jsonString
	 * @return boolean
	 */
	private function isValidJson($jsonString)
	{
		json_decode($jsonString);
		return (json_last_error() === JSON_ERROR_NONE) ? true : false;
	}
}
