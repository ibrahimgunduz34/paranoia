<?php
namespace Paranoia\Pos\Gvp;

use JMS\Serializer\Annotation as JMS;

/**
 * Class GVPSRequest
 * @package Paranoia\Pos\Gvp
 * @JMS\XmlRoot('GVPSRequest')
 */
class GVPSRequest
{
    /**
     * @var string
     * @JMS\SerializedName("Version")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $version;

    /**
     * @var string
     * @JMS\SerializedName("Mode")
     * @@JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     */
    private $mode;

    /**
     * @var \Paranoia\Pos\Gvp\Terminal
     * @JMS\Type("\Paranoia\Pos\Gvp\Terminal")
     * @JMS\SerializedName("Terminal")
     * @JMS\XmlElement(cdata=false)
     */
    private $terminal;

    /**
     * @var \Paranoia\Pos\Gvp\Order
     * @JMS\Type("\Paranoia\Pos\Gvp\Order")
     * @JMS\SerializedName("Order")
     * @JMS\XmlElement(cdata=false)
     */
    private $order;

    /**
     * @var \Paranoia\Pos\Gvp\Customer
     * @JMS\Type("\Paranoia\Pos\Gvp\Customer")
     * @JMS\SerializedName("Customer")
     * @JMS\XmlElement(cdata=false)
     */
    private $customer;

    /**
     * @var \Paranoia\Pos\Gvp\Transaction
     * @JMS\Type("\Paranoia\Pos\Gvp\Transaction")
     * @JMS\SerializedName("Transaction")
     * @JMS\XmlElement(cdata=false)
     */
    private $transaction;

    /**
     * @var \Paranoia\Pos\Gvp\Card
     * @JMS\Type("\Paranoia\Pos\Gvp\Card")
     * @JMS\SerializedName("Card")
     * @JMS\XmlElement(cdata=false)
     */
    private $card;

    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param \Paranoia\Pos\Gvp\Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Gvp\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
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
     * @param \Paranoia\Pos\Gvp\Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Gvp\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param \Paranoia\Pos\Gvp\Terminal $terminal
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Gvp\Terminal
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param \Paranoia\Pos\Gvp\Transaction $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return \Paranoia\Pos\Gvp\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
} 