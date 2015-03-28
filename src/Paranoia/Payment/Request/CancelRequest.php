<?php
namespace Paranoia\Payment\Request;

class CancelRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $authCode;

    /**
     * @param string $authCode
     * @return \Paranoia\Payment\Request\CancelRequest
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param string $orderId
     * @return \Paranoia\Payment\Request\CancelRequest
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $transactionId
     * @return \Paranoia\Payment\Request\CancelRequest
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
} 