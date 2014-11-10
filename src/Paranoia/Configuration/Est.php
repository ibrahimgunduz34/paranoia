<?php
namespace Paranoia\Configuration;


class Est implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $ThreeDSecureUrl;

    /**
     * @param string $ThreeDSecureUrl
     */
    public function setThreeDSecureUrl($ThreeDSecureUrl)
    {
        $this->ThreeDSecureUrl = $ThreeDSecureUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getThreeDSecureUrl()
    {
        return $this->ThreeDSecureUrl;
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
} 