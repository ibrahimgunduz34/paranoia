<?php
namespace Paranoia\Transaction;


class Request implements TransactionInterface
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
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var \Paranoia\Transaction\Resource\ResourceInterface
     */
    private $resource;

    /**
     * @var int
     */
    private $installment;

    /**
     * @param string $transactionId
     * @return $this
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

    /**
     * @param float $amount
     * @return $this;
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
     * @return $this;
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
     * @param int $installment
     * @return $this;
     */
    public function setInstallment($installment)
    {
        $this->installment = $installment;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @param string $orderId
     * @return $this;
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
     * @param \Paranoia\Transaction\Resource\ResourceInterface $resource
     * @return $this;
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return \Paranoia\Transaction\Resource\ResourceInterface
     * @return $this;
     */
    public function getResource()
    {
        return $this->resource;
    }
} 