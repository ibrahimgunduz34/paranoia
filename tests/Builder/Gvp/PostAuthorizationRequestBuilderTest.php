<?php
namespace Paranoia\Test\Builder\Gvp;

use Paranoia\Core\Currency;
use Paranoia\Core\Formatter\IsoNumericCurrencyCodeFormatter;
use Paranoia\Core\Formatter\MoneyFormatter;
use Paranoia\Core\Formatter\SingleDigitInstallmentFormatter;
use Paranoia\Core\Request\Request;
use Paranoia\Gvp\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Gvp\Configuration\Gvp as GvpConfiguration;
use Paranoia\Gvp\Formatter\ExpireDateFormatter;
use PHPUnit\Framework\TestCase;

class PostAuthorizationRequestBuilderTest extends TestCase
{
    public function test()
    {
        $builder = $this->setupBuilder();
        $request = $this->setupRequest();
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/gvp/postauthorization_request.xml',
            $rawRequest
        );
    }

    protected function setupConfiguration()
    {
        $configuration = new GvpConfiguration();
        $configuration->setTerminalId('123456')
            ->setMode('TEST')
            ->setAuthorizationUsername('PROVAUT')
            ->setAuthorizationPassword('PROVAUT')
            ->setRefundUsername('PROVRFN')
            ->setRefundPassword('PROVRFN');
        return $configuration;
    }

    /**
     * @param bool $isPartial
     * @return Request
     */
    protected function setupRequest($isPartial=false)
    {
        $request = new Request();
        $request->setOrderId('123456')
            ->setTransactionId('123456')
            ->setAmount(25.4)
            ->setCurrency(Currency::CODE_EUR);
        return $request;
    }

    protected function setupBuilder()
    {
        return new PostAuthorizationRequestBuilder(
            $this->setupConfiguration(),
            new IsoNumericCurrencyCodeFormatter(),
            new MoneyFormatter(),
            new SingleDigitInstallmentFormatter(),
            new ExpireDateFormatter()
        );
    }
}