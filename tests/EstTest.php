<?php
namespace Paranoia;

use Paranoia\Est;
use PHPUnit\Framework\TestCase;

class EstTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    protected function setUp()
    {
        $this->configuration = $this->getMockBuilder(Configuration::class)
            ->getMock();

        $this->configuration->expects($this->any())
            ->method("get")
            ->will($this->returnValueMap(
                [
                    ['NAME', 'user'],
                    ['PASSWORD', 'password'],
                    ['CLIENT_ID', '1234567']
                ]
            ));
    }


    public function testPurchase()
    {
//        $client = new Est($this->configuration);
//        $client->purchase(new PurchaseRequest());
    }
}
