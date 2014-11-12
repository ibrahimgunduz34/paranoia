<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

class Customer
{
    /**
     * @var string
     * @JMS\SerializedName("IPAddress")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $ipAddress;

    /**
     * @var string
     * @JMS\SerializedName("EmailAddress")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $emailAddress;

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

} 