<?php
namespace Paranoia\Test\Gvp;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\TransactionType;
use Paranoia\Gvp\Configuration\Gvp;
use Paranoia\Gvp\Processor\CancelResponseProcessor;
use Paranoia\Gvp\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Gvp\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Gvp\Processor\RefundResponseProcessor;
use Paranoia\Gvp\Processor\SaleResponseProcessor;
use PHPUnit\Framework\TestCase;

class GvpProcessorFactoryTest extends TestCase
{
    public function test_valid_transaction_type()
    {
        /** @var Gvp $configuration */
        $configuration = $this->getMockBuilder(Gvp::class)->getMock();
        $factory = new \Paranoia\Gvp\GvpProcessorFactory($configuration);
        $this->assertInstanceOf(SaleResponseProcessor::class, $factory->createProcessor(TransactionType::SALE));
        $this->assertInstanceOf(RefundResponseProcessor::class, $factory->createProcessor(TransactionType::REFUND));
        $this->assertInstanceOf(CancelResponseProcessor::class, $factory->createProcessor(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var \Paranoia\Gvp\Configuration\Gvp $configuration */
        $configuration = $this->getMockBuilder(Gvp::class)->getMock();

        $factory = new \Paranoia\Gvp\GvpProcessorFactory($configuration);
        $factory->createProcessor('dummy');
    }
}