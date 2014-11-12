<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

class Card
{
    /**
     * @var string
     * @JMS\SerializedName("Number")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $number;

    /**
     * @var string
     * @JMS\SerializedName("ExpireDate")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $expireDate;

    /**
     * @var string
     * @JMS\SerializedName("CVV2")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $cvv2;

    /**
     * @param string $cvv2
     */
    public function setCvv2($cvv2)
    {
        $this->cvv2 = $cvv2;
        return $this;
    }

    /**
     * @return string
     */
    public function getCvv2()
    {
        return $this->cvv2;
    }

    /**
     * @param string $expireDate
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return $this->expireDate;
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
} 