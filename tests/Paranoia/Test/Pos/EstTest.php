<?php
namespace Paranoia\Test\Pos;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Paranoia\Configuration\Est;
use Paranoia\Pos\PosAbstract;
use \Paranoia\Pos\Est as EstPos;
use Paranoia\Transaction\Request;
use Paranoia\Transaction\Resource\Request\Card;
use \PHPUnit_Framework_TestCase;

class EstTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Paranoia\Configuration\ConfigurationInterface
     */
    private $config;

    /**
     * @var \Paranoia\Transaction\Resource\Request\Card
     */
    private $testCard;

    public function setUp()
    {
        parent::setUp();

        AnnotationRegistry::registerLoader('class_exists');

        $config = new Est();
        $config->setApiUrl('https://entegrasyon.asseco-see.com.tr/fim/api')
            ->setClientId('100100000')
            ->setMode('TEST')
            ->setName('AKTESTAPI')
            ->setPassword('AKBANK01');

        $card = new Card();
        $card->setNumber('5406675406675403')
            ->setSecurityCode('000')
            ->setExpireMonth('12')
            ->setExpireYear('2015');

        $this->config = $config;
        $this->testCard = $card;
    }

    private function getPos()
    {
        return new EstPos($this->config);
    }

    private function createOrder($orderId = null, $amount = 10)
    {
        $request = new Request();
        $request->setResource($this->testCard)
            ->setOrderId($orderId == null ? 'A' . microtime() : $orderId)
            ->setAmount($amount)
            ->setCurrency(PosAbstract::CURRENCY_CODE_TRL);
        return $request;
    }

    public function testSale()
    {
        $order = $this->createOrder();
        $pos = $this->getPos();
        $response = $pos->sale($order);
        $this->assertTrue($response->isApproved());
    }

    public function testCancel()
    {
        $order = $this->createOrder();
        $pos = $this->getPos();
        $saleResponse = $pos->sale($order);
        $this->assertTrue($saleResponse->isApproved());

        $request = new Request();
        $request->setTransactionId($saleResponse->getTransactionId());
        $cancelResponse = $pos->cancel($request);
        $this->assertTrue($cancelResponse->isApproved());
    }

    public function testFullRefund()
    {
        $order = $this->createOrder();
        $pos = $this->getPos();
        $saleResponse = $pos->sale($order);
        $this->assertTrue($saleResponse->isApproved());

        $request = new Request();
        $request->setOrderId($order->getOrderId());
        $request->setAmount($order->getAmount());
        $request->setCurrency($order->getCurrency());
        $refundResponse = $pos->refund($request);
        $this->assertTrue($refundResponse->isApproved());
    }

    public function testPartialRefund()
    {
        $order = $this->createOrder();
        $pos = $this->getPos();
        $saleResponse = $pos->sale($order);
        $this->assertTrue($saleResponse->isApproved());

        $request = new Request();
        $request->setOrderId($order->getOrderId());
        $request->setAmount($order->getAmount() / 2);
        $request->setCurrency($order->getCurrency());
        $refundResponse = $pos->refund($request);
        $this->assertTrue($refundResponse->isApproved());

        $request = new Request();
        $request->setOrderId($order->getOrderId());
        $request->setAmount($order->getAmount() / 2);
        $request->setCurrency($order->getCurrency());
        $refundResponse = $pos->refund($request);
        $this->assertTrue($refundResponse->isApproved());
    }
} 