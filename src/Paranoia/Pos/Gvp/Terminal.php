<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

class Terminal
{
    /**
     * @var string
     * @JMS\SerializedName("ProvUserID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $provUserId;

    /**
     * @var string
     * @JMS\SerializedName("HashData")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $hashData;

    /**
     * @var string
     * @JMS\SerializedName("UserID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $userId;

    /**
     * @var string
     * @JMS\SerializedName("ID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $id;

    /**
     * @var string
     * @JMS\SerializedName("MerchantID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $merchantId;

    /**
     * @param string $hashData
     */
    public function setHashData($hashData)
    {
        $this->hashData = $hashData;
        return $this;
    }

    /**
     * @return string
     */
    public function getHashData()
    {
        return $this->hashData;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param string $provUserId
     */
    public function setProvUserId($provUserId)
    {
        $this->provUserId = $provUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvUserId()
    {
        return $this->provUserId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
} 