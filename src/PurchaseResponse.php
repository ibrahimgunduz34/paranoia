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
    public function isApproved(): bool
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     * @return PurchaseResponse
     */
    public function setIsApproved(bool $isApproved)
    {
        $this->isApproved = $isApproved;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PurchaseResponse
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return PurchaseResponse
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransId(): string
    {
        return $this->transId;
    }

    /**
     * @param string $transId
     * @return PurchaseResponse
     */
    public function setTransId(string $transId)
    {
        $this->transId = $transId;
        return $this;
    }
}
