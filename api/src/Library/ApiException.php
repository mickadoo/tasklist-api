<?php
/**
 * Created by PhpStorm.
 * User: mickadoo
 * Date: 02/01/15
 * Time: 19:05
 */

namespace MichaelDevery\Tasklist\Library;

class ApiException extends \Exception
{
    /**
     * @var int
     */
    protected $errorNum;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @param int $code
     * @param string $message
     */
    public function __construct($code, $message) {
        // some code
        $this->setErrorMessage($message);
        $this->setErrorNum($code);
    }

    /**
     * @return int
     */
    public function getErrorNum()
    {
        return $this->errorNum;
    }

    /**
     * @param int $errorNum
     */
    public function setErrorNum($errorNum)
    {
        $this->errorNum = $errorNum;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}