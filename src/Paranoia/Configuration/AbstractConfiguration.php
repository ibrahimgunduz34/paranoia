<?php
namespace Paranoia\Configuration;

class AbstractConfiguration
{

    /**
     * @var string
     */
    private $apiUrl;

    private $threeDSecureAuthUrl;

    /**
     * @param mixed $threeDSecureAuthUrl
     */
    public function setThreeDSecureAuthUrl($threeDSecureAuthUrl)
    {
        $this->threeDSecureAuthUrl = $threeDSecureAuthUrl;
    }

    /**
     * @return mixed
     */
    public function getThreeDSecureAuthUrl()
    {
        return $this->threeDSecureAuthUrl;
    }

    /**
     * @param string $apiUrl
     *
     * @return $this
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


}
