<?php
namespace Paranoia\Configuration;


class Posnet implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $mid;

    /**
     * @var string
     */
    private $tid;

    /**
     * @var string
     */
    private $apiUrl;

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