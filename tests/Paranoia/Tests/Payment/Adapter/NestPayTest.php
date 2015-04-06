<?php
namespace Paranoia\Tests\Payment\Adapter;

use Paranoia\Configuration\NestPay as NestPayConfig;
use Paranoia\Payment\Adapter\NestPay;
use Paranoia\Payment\Request\CancelRequest;
use Paranoia\Payment\Request\RefundRequest;
use Paranoia\Payment\Adapter\AdapterAbstract;
use Paranoia\Payment\Request\SaleRequest;

class NestPayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Paranoia\Payment\Adapter\AdapterAbstract
     */
    protected $adapter;

    protected function getConfiguration()
    {
        $configuration = new NestPayConfig();
        $configuration->setMode('PROD')
            ->setClientId('700655000100')
            ->setUsername('ISBANKAPI')
            ->setPassword('ISBANK07')
            ->setApiUrl('https://entegrasyon.asseco-see.com.tr/fim/api');
       return $configuration;
    }

    protected function setUp()
    {
        $this->adapter = new NestPay($this->getConfiguration());
    }

    private function createSaleRequest($orderId = null, $amount = 10, $installment = 1)
    {
        $request = new SaleRequest();
        $request->setOrderId($orderId != null ? $orderId : time() . rand(10000, 99999))
            ->setInstallment($installment)
            ->setAmount($amount)
            ->setCurrency(AdapterAbstract::CURRENCY_TRY)
            ->setCardNumber('4508034508034509')
            ->setExpireMonth(12)
            ->setExpireYear(16)
            ->setSecurityCode('000');
        return $request;
    }

    private function createRefundRequest($orderId, $amount)
    {
        $request = new RefundRequest();
        $request->setOrderId($orderId)
            ->setAmount($amount)
            ->setCurrency(AdapterAbstract::CURRENCY_TRY);
        return $request;
    }

    private function createCancelRequest($orderId, $transactionId)
    {
        $request = new CancelRequest();
        $request->setOrderId($orderId)
            ->setTransactionId($transactionId);
        return $request;
    }

    public function testSaleFullRefund()
    {
        $saleRequest = $this->createSaleRequest();
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());
    }

    public function testSaleAndPartialRefund()
    {
        $saleRequest = $this->createSaleRequest();
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());
    }

    public function testSaleAndCancel()
    {
        $saleRequest = $this->createSaleRequest();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $orderId = $saleRequest->getOrderId();
        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($orderId, $transactionId);

        /** @var $cancelResponse \Paranoia\Payment\Response\CancelResponse */
        $cancelResponse = $this->adapter->cancel($cancelRequest);
        $this->assertTrue($cancelResponse->isSuccess());
    }

    public function testSaleWithInstallmentFullRefund()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());
    }

    public function testSaleWithInstallmentAndPartialRefund()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess());
    }

    public function testSaleWithInstallmentAndCancel()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess());

        $orderId = $saleRequest->getOrderId();
        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($orderId, $transactionId);

        /** @var $cancelResponse \Paranoia\Payment\Response\CancelResponse */
        $cancelResponse = $this->adapter->cancel($cancelRequest);
        $this->assertTrue($cancelResponse->isSuccess());
    }
}