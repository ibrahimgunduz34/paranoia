<?php
namespace Paranoia;

use Paranoia\Exception\InvalidArgumentException;

class Configuration
{
    const API_URL = "api_url";

    private $collection = [];

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function add($key, $value)
    {
        $this->collection[$key] = $value;
    }

    /**
     * @param $key
     * @return string|int|bool
     */
    public function get($key)
    {
        if(!array_key_exists($key, $this->collection)) {
            throw new InvalidArgumentException("Unknown configuration $key");
        }
        return $this->collection[$key];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->collection;
    }
}
