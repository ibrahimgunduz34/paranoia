<?php
namespace Paranoia\Configuration;

class Gvp implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $terminalId;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $authUsername;

    /**
     * @var string
     */
    private $authPassword;

    /**
     * @var string
     */
    private $refundUsername;

    /**
     * @var string
     */
    private $refundPassword;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @param string $terminalId
     */
    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $authPassword
     */
    public function setAuthPassword($authPassword)
    {
        $this->authPassword = $authPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->authPassword;
    }

    /**
     * @param string $authUsername
     */
    public function setAuthUsername($authUsername)
    {
        $this->authUsername = $authUsername;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthUsername()
    {
        return $this->authUsername;
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
     * @param string $refundPassword
     */
    public function setRefundPassword($refundPassword)
    {
        $this->refundPassword = $refundPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefundPassword()
    {
        return $this->refundPassword;
    }

    /**
     * @param string $refundUsername
     */
    public function setRefundUsername($refundUsername)
    {
        $this->refundUsername = $refundUsername;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefundUsername()
    {
        return $this->refundUsername;
    }
} 