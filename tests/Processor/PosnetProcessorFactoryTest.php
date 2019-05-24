<?php
namespace Paranoia\Test\Processor;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\TransactionType;
use Paranoia\Posnet\Configuration\Posnet;
use Paranoia\Posnet\PosnetProcessorFactory;
use Paranoia\Posnet\Processor\CancelResponseProcessor;
use Paranoia\Posnet\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Posnet\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Posnet\Processor\RefundResponseProcessor;
use Paranoia\Posnet\Processor\SaleResponseProcessor;
use PHPUnit\Framework\TestCase;

class PosnetProcessorFactoryTest extends TestCase
{
    public function test_valid_transaction_type()
    {
        /** @var Posnet $configuration */
        $configuration = $this->getMockBuilder(Posnet::class)->getMock();
        $factory = new PosnetProcessorFactory($configuration);
        $this->assertInstanceOf(SaleResponseProcessor::class, $factory->createProcessor(TransactionType::SALE));
        $this->assertInstanceOf(RefundResponseProcessor::class, $factory->createProcessor(TransactionType::REFUND));
        $this->assertInstanceOf(CancelResponseProcessor::class, $factory->createProcessor(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var Posnet $configuration */
        $configuration = $this->getMockBuilder(Posnet::class)->getMock();

        $factory = new PosnetProcessorFactory($configuration);
        $factory->createProcessor('dummy');
    }
}