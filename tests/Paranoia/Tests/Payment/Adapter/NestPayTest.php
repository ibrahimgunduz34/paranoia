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
            ->setPassword('ISBANK07');
       return $configuration;
    }

    protected function setUp()
    {
        $this->adapter = new NestPay($this->getConfiguration());
    }

    private function createSaleRequest($orderId = null, $amount = 10, $installment = 1)
    {
        $request = new SaleRequest();
        $request->setOrderId($orderId != null ? $orderId : time())
            ->setInstallment($installment)
            ->setAmount($amount)
            ->setCurrency(AdapterAbstract::CURRENCY_TRY)
            ->setCardNumber('5406675406675403')
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

    private function createCancelRequest($transactionId = null)
    {
        $request = new CancelRequest();
        $request->setTransactionId($transactionId);
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

        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($transactionId);

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

        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($transactionId);

        /** @var $cancelResponse \Paranoia\Payment\Response\CancelResponse */
        $cancelResponse = $this->adapter->cancel($cancelRequest);
        $this->assertTrue($cancelResponse->isSuccess());
    }
}
 