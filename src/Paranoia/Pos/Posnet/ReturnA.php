<?php
namespace Paranoia\Pos\Posnet;

use JMS\Serializer\Annotation as JMS;

class ReturnA
{
    /**
     * @var string
     * @JMS\SerializedName("hostLogKey")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $hostLogKey;

    /**
     * @var string
     * @JMS\SerializedName("orderID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $orderId;

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
     * @param string $orderId
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
     * @param string $hostLogKey
     */
    public function setHostLogKey($hostLogKey)
    {
        $this->hostLogKey = $hostLogKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getHostLogKey()
    {
        return $this->hostLogKey;
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
} 