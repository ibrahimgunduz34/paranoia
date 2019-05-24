<?php
namespace Paranoia\Test\Processor;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\TransactionType;
use Paranoia\Nestpay\Configuration\NestPay;
use Paranoia\Nestpay\NestPayProcessorFactory;
use Paranoia\Nestpay\Processor\CancelResponseProcessor;
use Paranoia\Nestpay\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Nestpay\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Nestpay\Processor\RefundResponseProcessor;
use Paranoia\Nestpay\Processor\SaleResponseProcessor;
use PHPUnit\Framework\TestCase;

class NestPayProcessorFactoryTest extends TestCase
{
    public function test_valid_transaction_type()
    {
        /** @var \Paranoia\Nestpay\Configuration\NestPay $configuration */
        $configuration = $this->getMockBuilder(NestPay::class)->getMock();
        $factory = new NestPayProcessorFactory($configuration);
        $this->assertInstanceOf(SaleResponseProcessor::class, $factory->createProcessor(TransactionType::SALE));
        $this->assertInstanceOf(RefundResponseProcessor::class, $factory->createProcessor(TransactionType::REFUND));
        $this->assertInstanceOf(CancelResponseProcessor::class, $factory->createProcessor(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationResponseProcessor::class, $factory->createProcessor(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var NestPay $configuration */
        $configuration = $this->getMockBuilder(NestPay::class)->getMock();

        $factory = new NestPayProcessorFactory($configuration);
        $factory->createProcessor('dummy');
    }
}