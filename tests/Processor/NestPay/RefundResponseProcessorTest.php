<?php
namespace Paranoia\Test\Processor\NestPay;

use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Exception\BadResponseException;
use Paranoia\Core\Response;
use Paranoia\Nestpay\Processor\RefundResponseProcessor;
use PHPUnit\Framework\TestCase;

class RefundResponseProcessorTest extends TestCase
{
    public function test_success_response()
    {
        $rawResponse = file_get_contents(
            __DIR__ . '/../../samples/response/nestpay/refund_successful.xml'
        );

        /** @var \Paranoia\Core\Configuration\AbstractConfiguration $configuration */
        $configuration = $this->getMockBuilder(AbstractConfiguration::class)->getMock();
        $processor = new RefundResponseProcessor($configuration);
        $response = $processor->process($rawResponse);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(true, $response->isSuccess());
        $this->assertEquals('15335I94G07024820', $response->getTransactionId());
        $this->assertEquals('PB6356', $response->getAuthCode());
        $this->assertEquals('133577162970961', $response->getOrderId());
    }

    public function test_failed_response()
    {
        $rawResponse = file_get_contents(
            __DIR__ . '/../../samples/response/nestpay/refund_failed.xml'
        );

        /** @var AbstractConfiguration $configuration */
        $configuration = $this->getMockBuilder(AbstractConfiguration::class)->getMock();
        $processor = new RefundResponseProcessor($configuration);
        $response = $processor->process($rawResponse);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals(null, $response->getTransactionId());
        $this->assertEquals(null, $response->getOrderId());
        $this->assertEquals('05', $response->getResponseCode());
        $this->assertEquals('Error Message: Genel red  Host Message: [RC 05] Red Onaylanmadı - Do Not Honour', $response->getResponseMessage());
    }

    /**
     * @dataProvider badResponses
     * @param string $rawResponse
     */
    public function test_bad_response($rawResponse)
    {
        /** @var AbstractConfiguration $configuration */
        $configuration = $this->getMockBuilder(AbstractConfiguration::class)->getMock();
        $processor = new RefundResponseProcessor($configuration);

        $this->expectException(BadResponseException::class);
        $processor->process($rawResponse);
    }

    public function badResponses()
    {
        return [
            [null],
            [''],
            ['DUMMY'],
            [1],
        ];
    }

}
