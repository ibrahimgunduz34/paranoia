<?php
namespace Paranoia\Test\Formatter\NestPay;

use Paranoia\Nestpay\Formatter\ExpireDateFormatter;
use PHPUnit\Framework\TestCase;

class ExpireDateTest extends TestCase
{
    public function test()
    {
        $formatter = new ExpireDateFormatter();
        $this->assertEquals('02/1994', $formatter->format([2, 1994]));
    }
}