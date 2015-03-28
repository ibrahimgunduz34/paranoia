<?php
namespace Paranoia\Payment\Request;

class PreAuthorizationRequest implements RequestInterface
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
     * @var integer
     */
    private $installment;

    /**
     * @var string
     */
    private $cardNumber;

    /**
     * @var integer
     */
    private $securityCode;

    /**
     * @var integer
     */
    private $expireYear;

    /**
     * @var integer
     */
    private $expireMonth;

    /**
     * @param float $amount
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
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
     * @param string $cardNumber
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
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
     * @param string $currency
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
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
     * @param int $expireMonth
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
     */
    public function setExpireMonth($expireMonth)
    {
        $this->expireMonth = $expireMonth;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireMonth()
    {
        return $this->expireMonth;
    }

    /**
     * @param int $expireYear
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
     */
    public function setExpireYear($expireYear)
    {
        $this->expireYear = $expireYear;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireYear()
    {
        return $this->expireYear;
    }

    /**
     * @param int $installment
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
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
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
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
     * @param int $securityCode
     * @return \Paranoia\Payment\Request\PreAuthorizationRequest
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }
}