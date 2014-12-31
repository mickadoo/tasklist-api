<?php
namespace MichaelDevery\TaskList;

use MichaelDevery\Tasklist\Models\SerializableTrait;

class Response implements \JsonSerializable
{

    use SerializableTrait;

	/** @var int */
	private $code;
	/** @var string */
	private $resourceUrl;
	/** @var string */
	private $data;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    /**
     * @param string $resourceUrl
     */
    public function setResourceUrl($resourceUrl)
    {
        $this->resourceUrl = $resourceUrl;
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
