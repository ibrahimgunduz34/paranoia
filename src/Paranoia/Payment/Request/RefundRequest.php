<?php
namespace Paranoia\Payment\Request;

class RefundRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @param float $amount
     * @return \Paranoia\Payment\Request\RefundRequest
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $currency
     * @return \Paranoia\Payment\Request\RefundRequest
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $orderId
     * @return \Paranoia\Payment\Request\RefundRequest
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
} 