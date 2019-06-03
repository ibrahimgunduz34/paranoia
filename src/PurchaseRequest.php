<?php
namespace Paranoia;

class PurchaseRequest
{
    /** @var string */
    private $orderId;

    /** @var float */
    private $amount;

    //TODO: It's gonna be Currency enum that provided by a money library/implementation.
    private $currency;

    /** @var integer */
    private $installment;

    /** @var string */
    private $cardNumber;

    /** @var integer */
    private $cardExpireMonth;

    /** @var integer */
    private $cardExpireYear;

    /** @var integer */
    private $cardSecurityCode;

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return PurchaseRequest
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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
     * @param float $amount
     * @return PurchaseRequest
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return PurchaseRequest
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
     * @param int $installment
     * @return PurchaseRequest
     */
    public function setInstallment($installment)
    {
        $this->installment = $installment;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     * @return PurchaseRequest
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardExpireMonth()
    {
        return $this->cardExpireMonth;
    }

    /**
     * @param int $cardExpireMonth
     * @return PurchaseRequest
     */
    public function setCardExpireMonth($cardExpireMonth)
    {
        $this->cardExpireMonth = $cardExpireMonth;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardExpireYear()
    {
        return $this->cardExpireYear;
    }

    /**
     * @param int $cardExpireYear
     * @return PurchaseRequest
     */
    public function setCardExpireYear($cardExpireYear)
    {
        $this->cardExpireYear = $cardExpireYear;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    /**
     * @param int $cardSecurityCode
     * @return PurchaseRequest
     */
    public function setCardSecurityCode($cardSecurityCode)
    {
        $this->cardSecurityCode = $cardSecurityCode;
        return $this;
    }
}
