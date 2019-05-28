<?php
namespace Paranoia\Test\Gvp;

use Paranoia\Core\Exception\NotImplementedError;
use Paranoia\Core\TransactionType;
use Paranoia\Gvp\Builder\CancelRequestBuilder;
use Paranoia\Gvp\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Gvp\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Gvp\Builder\RefundRequestBuilder;
use Paranoia\Gvp\Builder\SaleRequestBuilder;
use Paranoia\Gvp\Configuration\Gvp as GvpConfiguration;
use Paranoia\Gvp\GvpBuilderFactory;
use PHPUnit\Framework\TestCase;

class GvpBuilderFactoryTest extends TestCase
{
    public function test_valid_transaction_types()
    {
        /** @var GvpConfiguration $configuration */
        $configuration = $this->getMockBuilder(GvpConfiguration::class)->getMock();
        $factory = new GvpBuilderFactory($configuration);
        $this->assertInstanceOf(SaleRequestBuilder::class, $factory->createBuilder(TransactionType::SALE));
        $this->assertInstanceOf(RefundRequestBuilder::class, $factory->createBuilder(TransactionType::REFUND));
        $this->assertInstanceOf(CancelRequestBuilder::class, $factory->createBuilder(TransactionType::CANCEL));
        $this->assertInstanceOf(PreAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::PRE_AUTHORIZATION));
        $this->assertInstanceOf(PostAuthorizationRequestBuilder::class, $factory->createBuilder(TransactionType::POST_AUTHORIZATION));
    }

    public function test_invalid_transaction_type()
    {
        $this->expectException(NotImplementedError::class);

        /** @var GvpConfiguration $configuration */
        $configuration = $this->getMockBuilder(GvpConfiguration::class)->getMock();

        $factory = new GvpBuilderFactory($configuration);
        $factory->createBuilder('Dummy');
    }
}
