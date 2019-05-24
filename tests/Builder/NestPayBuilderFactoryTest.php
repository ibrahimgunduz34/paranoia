<?php
namespace Paranoia\Test\Builder;

use Paranoia\Core\Exception\NotImplementedError;
use Paranoia\Core\TransactionType;
use Paranoia\Nestpay\Builder\CancelRequestBuilder;
use Paranoia\Nestpay\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Nestpay\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Nestpay\Builder\RefundRequestBuilder;
use Paranoia\Nestpay\Builder\SaleRequestBuilder;
use Paranoia\Nestpay\Configuration\NestPay;
use Paranoia\Nestpay\Configuration\NestPay as NestPayConfiguration;
use PHPUnit\Framework\TestCase;


class NestPayBuilderFactoryTest extends TestCase
{
    public function test_valid_transaction_types()
    {
        /** @var NestPayConfiguration $configuration */
        $configuration = $this->getMockBuilder(NestPayConfiguration::class)->getMock();

        $factory = new \Paranoia\Nestpay\NestPayBuilderFactory($configuration);
        $this->assertInstanceOf(SaleRequestBuilder::class, $factory->createBuilder(TransactionType::SALE));
        $this->assertInstanceOf(RefundRequestBuilder::class, $factory->createBuilder(TransactionType::REFUND));
        $this->assertInstanceOf(CancelRequestBuilder::class, $factory->createBuilder(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(NotImplementedError::class);

        /** @var NestPayConfiguration $configuration */
        $configuration = $this->getMockBuilder(NestPayConfiguration::class)->getMock();

        $factory = new \Paranoia\Nestpay\NestPayBuilderFactory($configuration);
        $factory->createBuilder('Dummy');
    }
}
