<?php
namespace Paranoia\Test\Nestpay\Builder;

use Paranoia\Core\Currency;
use Paranoia\Core\Formatter\DecimalFormatter;
use Paranoia\Core\Formatter\IsoNumericCurrencyCodeFormatter;
use Paranoia\Core\Formatter\SingleDigitInstallmentFormatter;
use Paranoia\Core\Request\Request;
use Paranoia\Core\Request\Resource\Card;
use Paranoia\Nestpay\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Nestpay\Configuration\NestPay as NestPayConfiguration;
use Paranoia\Nestpay\Formatter\ExpireDateFormatter;
use PHPUnit\Framework\TestCase;

class PreAuthorizationRequestBuilderTest extends TestCase
{
    public function test_pre_auth()
    {
        $builder = $this->setupBuilder();

        $request = $this->setupRequest();
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/nestpay/preauthorization_request.xml',
            $rawRequest
        );
    }

    protected function setupConfiguration()
    {
        $configuration = new NestPayConfiguration();
        $configuration->setClientId('123456')
            ->setMode('TEST')
            ->setUsername('TEST')
            ->setPassword('TEST');
        return $configuration;
    }

    /**
     * @param bool $setInstallment
     * @return Request
     */
    protected function setupRequest()
    {
        $request = new Request();
        $request->setOrderId('123456')
            ->setAmount(25.4)
            ->setCurrency(Currency::CODE_EUR);

        $card = new Card();
        $card->setNumber('1501501501501500')
            ->setSecurityCode('000')
            ->setExpireMonth(1)
            ->setExpireYear(2020);
        $request->setResource($card);

        return $request;
    }

    protected function setupBuilder()
    {
        return new PreAuthorizationRequestBuilder(
            $this->setupConfiguration(),
            new IsoNumericCurrencyCodeFormatter(),
            new DecimalFormatter(),
            new SingleDigitInstallmentFormatter(),
            new ExpireDateFormatter()
        );
    }
}
