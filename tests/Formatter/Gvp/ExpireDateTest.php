<?php
namespace Paranoia\Test\Formatter\Gvp;

use Paranoia\Gvp\Formatter\ExpireDateFormatter;
use PHPUnit\Framework\TestCase;

class ExpireDateTest extends TestCase
{
    public function test()
    {
        $formatter = new ExpireDateFormatter();
        $this->assertEquals('0294', $formatter->format([2, 1994]));
    }
}