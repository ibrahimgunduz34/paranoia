<?php
namespace Paranoia\Tests\Payment\Adapter;

use Paranoia\Configuration\Posnet as PosnetConfig;
use Paranoia\Payment\Adapter\Posnet;
use Paranoia\Payment\Request\CancelRequest;
use Paranoia\Payment\Request\RefundRequest;
use Paranoia\Payment\Adapter\AdapterAbstract;
use Paranoia\Payment\Request\SaleRequest;

class PosnetTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \Paranoia\Payment\Adapter\AdapterAbstract
     */
    protected $adapter;

    protected function getConfiguration()
    {
        $configuration = new PosnetConfig();
        $configuration->setMerchantId('6797752273')
            ->setTerminalId('67006667')
            ->setApiUrl('http://setmpos.ykb.com/PosnetWebService/XML');
        return $configuration;
    }

    protected function setUp()
    {
        $this->adapter = new Posnet($this->getConfiguration());
    }

    private function createSaleRequest($orderId = null, $amount = 10, $installment = 1)
    {
        $request = new SaleRequest();
        $request->setOrderId($orderId != null ? $orderId : time() . rand(10000, 99999))
            ->setInstallment($installment)
            ->setAmount($amount)
            ->setCurrency(AdapterAbstract::CURRENCY_TRY)
            ->setCardNumber('4506347043358536')
            ->setExpireMonth(8)
            ->setExpireYear(19)
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
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());
    }

    public function testSaleAndPartialRefund()
    {
        $saleRequest = $this->createSaleRequest();
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());
    }

    public function testSaleAndCancel()
    {
        $saleRequest = $this->createSaleRequest();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $orderId = $saleRequest->getOrderId();
        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($orderId, $transactionId);

        /** @var $cancelResponse \Paranoia\Payment\Response\CancelResponse */
        $cancelResponse = $this->adapter->cancel($cancelRequest);
        $this->assertTrue($cancelResponse->isSuccess(), $cancelResponse->getMessage());
    }

    public function testSaleWithInstallmentFullRefund()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());
    }

    public function testSaleWithInstallmentAndPartialRefund()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);
        $orderId = $saleRequest->getOrderId();
        $amount = $saleRequest->getAmount();

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());

        $refundRequest = $this->createRefundRequest($orderId, $amount / 2);

        /** @var $refundResponse \Paranoia\Payment\Response\RefundResponse */
        $refundResponse = $this->adapter->refund($refundRequest);
        $this->assertTrue($refundResponse->isSuccess(), $refundResponse->getMessage());
    }

    public function testSaleWithInstallmentAndCancel()
    {
        $saleRequest = $this->createSaleRequest(null, 10, 3);

        /** @var $saleResponse \Paranoia\Payment\Response\SaleResponse */
        $saleResponse = $this->adapter->sale($saleRequest);
        $this->assertTrue($saleResponse->isSuccess(), $saleResponse->getMessage());

        $orderId = $saleRequest->getOrderId();
        $transactionId = $saleResponse->getTransactionId();

        $cancelRequest = $this->createCancelRequest($orderId, $transactionId);

        /** @var $cancelResponse \Paranoia\Payment\Response\CancelResponse */
        $cancelResponse = $this->adapter->cancel($cancelRequest);
        $this->assertTrue($cancelResponse->isSuccess(), $cancelResponse->getMessage());
    }
}
 