<?php
namespace Paranoia\Test\Builder;

use Paranoia\Core\Exception\NotImplementedError;
use Paranoia\Core\TransactionType;
use Paranoia\Posnet\Builder\CancelRequestBuilder;
use Paranoia\Posnet\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Posnet\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Posnet\Builder\RefundRequestBuilder;
use Paranoia\Posnet\Builder\SaleRequestBuilder;
use Paranoia\Posnet\Configuration\Posnet as PosnetConfiguration;
use PHPUnit\Framework\TestCase;

class PosnetBuilderFactoryTest extends TestCase
{
    public function test_valid_transaction_types()
    {
        /** @var PosnetConfiguration $configuration */
        $configuration = $this->getMockBuilder(PosnetConfiguration::class)->getMock();

        $factory = new \Paranoia\Posnet\PosnetBuilderFactory($configuration);
        $this->assertInstanceOf(SaleRequestBuilder::class, $factory->createBuilder(TransactionType::SALE));
        $this->assertInstanceOf(RefundRequestBuilder::class, $factory->createBuilder(TransactionType::REFUND));
        $this->assertInstanceOf(CancelRequestBuilder::class, $factory->createBuilder(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(NotImplementedError::class);

        /** @var PosnetConfiguration $configuration */
        $configuration = $this->getMockBuilder(PosnetConfiguration::class)->getMock();

        $factory = new \Paranoia\Posnet\PosnetBuilderFactory($configuration);
        $factory->createBuilder('Dummy');
    }

    protected function setupConfiguration()
    {
        $configuration = new PosnetConfiguration();;
        return $configuration;
    }
}
