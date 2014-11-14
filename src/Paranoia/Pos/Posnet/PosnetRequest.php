<?php
namespace Paranoia\Pos\Posnet;

use JMS\Serializer\Annotation as JMS;

/**
 * Class GVPSRequest
 * @package Paranoia\Pos\PosnetRequest
 * @JMS\XmlRoot('posnetRequest')
 */
class PosnetRequest
{
    /**
     * @var string
     * @JMS\SerializedName("username")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $username;

    /**
     * @var string
     * @JMS\SerializedName("password")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $password;

    /**
     * @var string
     * @JMS\SerializedName("mid")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $mid;

    /**
     * @var string
     * @JMS\SerializedName("tid")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $tid;

    /**
     * @var \Paranoia\Pos\Posnet\Sale
     * @JMS\Type("\Paranoia\Pos\Posnet\Sale")
     * @JMS\SerializedName("sale")
     * @JMS\XmlElement(cdata=false)
     */
    private $sale;

    /**
     * @var \Paranoia\Pos\Posnet\Reverse
     * @JMS\Type("\Paranoia\Pos\Posnet\Reverse")
     * @JMS\SerializedName("reverse")
     * @JMS\XmlElement(cdata=false)
     */
    private $reverse;

    /**
     * @var \Paranoia\Pos\Posnet\ReturnA
     * @JMS\Type("\Paranoia\Pos\Posnet\ReturnA")
     * @JMS\SerializedName("return")
     * @JMS\XmlElement(cdata=false)
     */
    private $return;

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
        return $this;
    }

    /**
     * @return string
     */
    public function getMid()
    {
        return $this->mid;
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
     * @param \Paranoia\Pos\Posnet\ReturnA $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Posnet\ReturnA
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param \Paranoia\Pos\Posnet\Reverse $reverse
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Posnet\Reverse
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * @param \Paranoia\Pos\Posnet\Sale $sale
     */
    public function setSale($sale)
    {
        $this->sale = $sale;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Posnet\Sale
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * @param string $tid
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
        return $this;
    }

    /**
     * @return string
     */
    public function getTid()
    {
        return $this->tid;
    }
} 