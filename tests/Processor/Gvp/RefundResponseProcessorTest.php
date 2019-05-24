<?php
namespace Paranoia\Test\Processor\Gvp;

use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Exception\BadResponseException;
use Paranoia\Core\Response;
use Paranoia\Gvp\Processor\RefundResponseProcessor;
use PHPUnit\Framework\TestCase;

class RefundResponseProcessorTest extends TestCase
{
    public function test_success_response()
    {
        $rawResponse = file_get_contents(
            __DIR__ . '/../../samples/response/gvp/refund_successful.xml'
        );

        /** @var \Paranoia\Core\Configuration\AbstractConfiguration $configuration */
        $configuration = $this->getMockBuilder(AbstractConfiguration::class)->getMock();
        $processor = new RefundResponseProcessor($configuration);
        $response = $processor->process($rawResponse);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(true, $response->isSuccess());
        $this->assertEquals('311616674771', $response->getTransactionId());
        $this->assertEquals('489787', $response->getAuthCode());
        $this->assertEquals('1476', $response->getOrderId());
    }

    public function test_failed_response()
    {
        $rawResponse = file_get_contents(
            __DIR__ . '/../../samples/response/gvp/refund_failed.xml'
        );

        /** @var \Paranoia\Core\Configuration\AbstractConfiguration $configuration */
        $configuration = $this->getMockBuilder(AbstractConfiguration::class)->getMock();
        $processor = new \Paranoia\Gvp\Processor\RefundResponseProcessor($configuration);
        $response = $processor->process($rawResponse);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals(null, $response->getTransactionId());
        $this->assertEquals(null, $response->getOrderId());
        $this->assertEquals('0214', $response->getResponseCode());
        $this->assertEquals('Error Message: ›ade tutar˝, sat˝˛ tutar˝ndan b¸y¸k olamaz System Error Message: ErrorId: 0214', $response->getResponseMessage());
    }

    /**
     * @dataProvider badResponses
     * @param string $rawResponse
     */
    public function test_bad_response($rawResponse)
    {
        /** @var \Paranoia\Core\Configuration\AbstractConfiguration $configuration */
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
