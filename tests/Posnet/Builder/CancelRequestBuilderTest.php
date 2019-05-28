<?php
namespace Paranoia\Test\Posnet\Builder;

use Paranoia\Core\Formatter\MoneyFormatter;
use Paranoia\Core\Formatter\MultiDigitInstallmentFormatter;
use Paranoia\Core\Request\Request;
use Paranoia\Posnet\Builder\CancelRequestBuilder;
use Paranoia\Posnet\Configuration\Posnet as PosnetConfiguration;
use Paranoia\Posnet\Formatter\CustomCurrencyCodeFormatter;
use Paranoia\Posnet\Formatter\ExpireDateFormatter;
use Paranoia\Posnet\Formatter\OrderIdFormatter;
use PHPUnit\Framework\TestCase;

class CancelRequestBuilderTest extends TestCase
{
    public function test()
    {
        $builder = $this->setupBuilder();
        $request = $this->setupRequest();
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/posnet/cancel_request.xml',
            $rawRequest
        );
    }

    protected function setupConfiguration()
    {
        $configuration = new PosnetConfiguration();
        $configuration->setMerchantId('213456')
            ->setTerminalId('654321')
            ->setUsername('TEST')
            ->setPassword('TEST');
        return $configuration;
    }

    /**
     * @return Request
     */
    protected function setupRequest()
    {
        $request = new Request();
        $request->setTransactionId('12345678901');
        return $request;
    }

    protected function setupBuilder()
    {
        return new CancelRequestBuilder(
            $this->setupConfiguration(),
            new CustomCurrencyCodeFormatter(),
            new MoneyFormatter(),
            new MultiDigitInstallmentFormatter(),
            new ExpireDateFormatter(),
            new OrderIdFormatter()
        );
    }
}