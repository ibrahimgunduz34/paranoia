<?php
namespace Paranoia\Payment\Response;

abstract class ResponseAbstract
{
    /**
     * @var boolean
     */
    protected $isSuccess;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param string $code
     * @return \Paranoia\Payment\Response\ResponseAbstract
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param boolean $isSuccess
     * @return \Paranoia\Payment\Response\ResponseAbstract
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * @param string $message
     * @return \Paranoia\Payment\Response\ResponseAbstract
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
} 