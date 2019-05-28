<?php
namespace Paranoia\Test\Posnet\Builder;

use Paranoia\Core\Currency;
use Paranoia\Core\Formatter\MoneyFormatter;
use Paranoia\Core\Formatter\MultiDigitInstallmentFormatter;
use Paranoia\Core\Request\Request;
use Paranoia\Core\Request\Resource\Card;
use Paranoia\Posnet\Builder\SaleRequestBuilder;
use Paranoia\Posnet\Configuration\Posnet as PosnetConfiguration;
use Paranoia\Posnet\Formatter\CustomCurrencyCodeFormatter;
use Paranoia\Posnet\Formatter\ExpireDateFormatter;
use Paranoia\Posnet\Formatter\OrderIdFormatter;
use PHPUnit\Framework\TestCase;

class SaleRequestBuilderTest extends TestCase
{
    public function test_sales_with_single_installment()
    {
        $builder = $this->setupBuilder();
        $request = $this->setupRequest();
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/posnet/sale_request_eur.xml',
            $rawRequest
        );
    }

    public function test_sales_with_multi_installment()
    {
        $builder = $this->setupBuilder();
        $request = $this->setupRequest(true);
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/posnet/sale_request_eur_with_installment.xml',
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
     * @param bool $setInstallment
     * @return Request
     */
    protected function setupRequest($setInstallment=false)
    {
        $request = new Request();
        $request->setOrderId('123456')
            ->setAmount(25.4)
            ->setCurrency(Currency::CODE_EUR);
        if($setInstallment) {
            $request->setInstallment(3);
        }

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
        return new SaleRequestBuilder(
            $this->setupConfiguration(),
            new CustomCurrencyCodeFormatter(),
            new MoneyFormatter(),
            new MultiDigitInstallmentFormatter(),
            new ExpireDateFormatter(),
            new OrderIdFormatter()
        );
    }
}