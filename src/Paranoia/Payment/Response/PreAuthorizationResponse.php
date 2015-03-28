<?php
namespace Paranoia\Payment\Response;

class PreAuthorizationResponse extends ResponseAbstract
{
    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @param string $orderId
     * @return \Paranoia\Payment\Response\PreAuthorizationResponse
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
     * @return \Paranoia\Payment\Response\PreAuthorizationResponse
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