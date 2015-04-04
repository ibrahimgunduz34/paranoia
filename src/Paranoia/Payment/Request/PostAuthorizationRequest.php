<?php
namespace Paranoia\Payment\Request;

class PostAuthorizationRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @param string $orderId
     * @return \Paranoia\Payment\Request\PostAuthorizationRequest
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