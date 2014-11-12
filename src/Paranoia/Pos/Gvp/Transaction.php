<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

class Transaction
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
     * @JMS\SerializedName("InstallmentCnt")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $installmentCnt;

    /**
     * @var string
     * @JMS\SerializedName("Amount")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $amount;

    /**
     * @var string
     * @JMS\SerializedName("CurrencyCode")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $currencyCode;

    /**
     * @var string
     * @JMS\SerializedName("CardholderPresentCode")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $cardHolderPresentCode;

    /**
     * @var string
     * @JMS\SerializedName("MotoInd")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $motoInd;

    /**
     * @var string
     * @JMS\SerializedName("OriginalRetrefNum")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $originalRetRefNum;

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
     * @param string $cardHolderPresentCode
     */
    public function setCardHolderPresentCode($cardHolderPresentCode)
    {
        $this->cardHolderPresentCode = $cardHolderPresentCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardHolderPresentCode()
    {
        return $this->cardHolderPresentCode;
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
     * @param string $installmentCnt
     */
    public function setInstallmentCnt($installmentCnt)
    {
        $this->installmentCnt = $installmentCnt;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstallmentCnt()
    {
        return $this->installmentCnt;
    }

    /**
     * @param string $motoInd
     */
    public function setMotoInd($motoInd)
    {
        $this->motoInd = $motoInd;
        return $this;
    }

    /**
     * @return string
     */
    public function getMotoInd()
    {
        return $this->motoInd;
    }

    /**
     * @param string $originalRetRefNum
     */
    public function setOriginalRetRefNum($originalRetRefNum)
    {
        $this->originalRetRefNum = $originalRetRefNum;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalRetRefNum()
    {
        return $this->originalRetRefNum;
    }
} 