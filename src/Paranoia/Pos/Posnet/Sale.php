<?php
namespace Paranoia\Pos\Posnet;

use JMS\Serializer\Annotation as JMS;

class Sale
{
    /**
     * @var string
     * @JMS\SerializedName("ccnum")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $ccno;

    /**
     * @var string
     * @JMS\SerializedName("expDate")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $expDate;

    /**
     * @var string
     * @JMS\SerializedName("cvc")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $cvc;

    /**
     * @var string
     * @JMS\SerializedName("amount")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $amount;

    /**
     * @var string
     * @JMS\SerializedName("currencyCode")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $currencyCode;

    /**
     * @var string
     * @JMS\SerializedName("orderID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $orderId;

    /**
     * @var string
     * @JMS\SerializedName("installment")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $installment;

    /**
     * @var string
     * @JMS\SerializedName("extraPoint")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $extraPoint;

    /**
     * @var string
     * @JMS\SerializedName("multiplePoint")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $multiplePoint;

    /**
     * @param string $orderId
     * @return $this
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
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $ccno
     */
    public function setCcno($ccno)
    {
        $this->ccno = $ccno;
        return $this;
    }

    /**
     * @return string
     */
    public function getCcno()
    {
        return $this->ccno;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $cvc
     */
    public function setCvc($cvc)
    {
        $this->cvc = $cvc;
        return $this;
    }

    /**
     * @return string
     */
    public function getCvc()
    {
        return $this->cvc;
    }

    /**
     * @param string $expDate
     */
    public function setExpDate($expDate)
    {
        $this->expDate = $expDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpDate()
    {
        return $this->expDate;
    }

    /**
     * @param string $extraPoint
     */
    public function setExtraPoint($extraPoint)
    {
        $this->extraPoint = $extraPoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtraPoint()
    {
        return $this->extraPoint;
    }

    /**
     * @param string $installment
     */
    public function setInstallment($installment)
    {
        $this->installment = $installment;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @param string $multiplePoint
     */
    public function setMultiplePoint($multiplePoint)
    {
        $this->multiplePoint = $multiplePoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getMultiplePoint()
    {
        return $this->multiplePoint;
    }
} 