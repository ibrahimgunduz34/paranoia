<?php
namespace Paranoia\Pos\Est;

use JMS\Serializer\Annotation as JMS;

/**
 * Class CC5Request
 * @package Paranoia\Pos\Est
 * @JMS\XmlRoot("CC5Request")
 */
class CC5Request
{
    /**
     * @var string
     * @JMS\SerializedName("Type")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $type;

    /**
     * @var string
     * @JMS\SerializedName("OrderId")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $orderId;

    /**
     * @var string
     * @JMS\SerializedName("Total")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $total;

    /**
     * @var float
     * @JMS\SerializedName("Currency")
     * @@JMS\Type("float")
     * @JMS\XmlElement(cdata=false)
     */
    private $currency;

    /**
     * @var string
     * @JMS\SerializedName("Number")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $number;

    /**
     * @var string
     * @JMS\SerializedName("Cvv2Val")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $cvc2Val;

    /**
     * @var string
     * @JMS\SerializedName("Expires")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $expires;

    /**
     * @var string
     * @JMS\SerializedName("Name")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $name;

    /**
     * @var string
     * @JMS\SerializedName("ClientId")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $clientId;

    /**
     * @var string
     * @JMS\SerializedName("Password")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $password;

    /**
     * @var string
     * @JMS\SerializedName("Mode")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $mode;

    /**
     * @var string
     * @JMS\SerializedName("Taksit")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $taksit;

    /**
     * @var string
     * @JMS\SerializedName("TransId")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $transId;

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param float $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $cvc2Val
     */
    public function setCvc2Val($cvc2Val)
    {
        $this->cvc2Val = $cvc2Val;
        return $this;
    }

    /**
     * @return string
     */
    public function getCvc2Val()
    {
        return $this->cvc2Val;
    }

    /**
     * @param string $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
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

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $taksit
     */
    public function setTaksit($taksit)
    {
        $this->taksit = $taksit;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaksit()
    {
        return $this->taksit;
    }

    /**
     * @param string $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $transId
     */
    public function setTransId($transId)
    {
        $this->transId = $transId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransId()
    {
        return $this->transId;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}