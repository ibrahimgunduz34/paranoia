<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

class Order
{
    /**
     * @var string
     * @JMS\SerializedName("OrderID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $orderId;

    /**
     * @var string
     * @JMS\SerializedName("GroupID")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $groupId;

    /**
     * @var string
     * @JMS\SerializedName("Description")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $description;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->groupId;
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