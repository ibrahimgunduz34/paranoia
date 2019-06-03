<?php
namespace Paranoia;

class PurchaseResponse
{
    /** @var bool */
    private $isApproved;

    /** @var string */
    private $code;

    /** @var string */
    private $message;

    /** @var string */
    private $transId;

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     * @return PurchaseResponse
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
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
     * @param string $code
     * @return PurchaseResponse
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return PurchaseResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransId()
    {
        return $this->transId;
    }

    /**
     * @param string $transId
     * @return PurchaseResponse
     */
    public function setTransId($transId)
    {
        $this->transId = $transId;
        return $this;
    }
}
