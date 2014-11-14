<?php
namespace Paranoia\Pos\Posnet;

use JMS\Serializer\Annotation as JMS;

class Reverse
{
    /**
     * @var string
     * @JMS\SerializedName("transaction")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $transaction;

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
     * @param string $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
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
} 