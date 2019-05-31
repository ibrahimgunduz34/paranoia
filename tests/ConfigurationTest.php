<?php

namespace Paranoia\Test;

use Paranoia\Configuration;
use Paranoia\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var ReflectionProperty
     */
    private $configColletionRef;

    protected function setUp()
    {
        $this->configuration = new Configuration();
        $reflection = new \ReflectionObject($this->configuration);
        $this->configColletionRef = $reflection->getProperty("collection");
        $this->configColletionRef->setAccessible(true);
    }

    public function testGet()
    {
        $value = time();
        $collection = ['key' => $value];
        $this->configColletionRef->setValue($this->configuration, $collection);
        $this->assertEquals($value, $this->configuration->get("key"));
    }

    public function testGet_unknown_key_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->configuration->get("some_key");
    }

    public function testAdd()
    {
        $value = time();
        $this->configuration->add("key", $value);

        $collection = $this->configColletionRef->getValue($this->configuration);
        $this->assertEquals($collection['key'], $value);
    }

    public function testAdd_override_value()
    {
        $value = time();
        $this->configuration->add("key", $value);
        $collection = $this->configColletionRef->getValue($this->configuration);
        $this->assertEquals($collection['key'], $value);

        $value = time();
        $this->configuration->add("key", time());
        $collection = $this->configColletionRef->getValue($this->configuration);
        $this->assertEquals($collection['key'], $value);
    }

    public function testToArray()
    {
        $collection = ['some_key' => "some_value", "another_key" => "another_value"];
        $this->configColletionRef->setValue($this->configuration, $collection);
        $this->assertEquals($collection, $this->configuration->toArray());
    }
}
