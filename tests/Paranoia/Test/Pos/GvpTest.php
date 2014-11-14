<?php
namespace Paranoia\Test\Pos;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Paranoia\Configuration\Gvp;
use Paranoia\Pos\PosAbstract;
use \Paranoia\Pos\Gvp as GvpPos;
use Paranoia\Transaction\Request;
use Paranoia\Transaction\Resource\Request\Card;
use \PHPUnit_Framework_TestCase;

class GvpTest extends PHPUnit_Framework_TestCase
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

        $config = new Gvp();
        $config->setApiUrl('https://sanalposprovtest.garanti.com.tr/VPServlet')
            ->setMerchantId('7000679')
            ->setTerminalId('30691242')
            ->setAuthUsername('PROVAUT')
            ->setAuthPassword('123qweASD')
            ->setRefundUsername('PROVRFN')
            ->setRefundPassword('123qweASD');

        $card = new Card();
        $card->setNumber('4059175701021869')
            ->setSecurityCode('000')
            ->setExpireMonth('01')
            ->setExpireYear('2020');

        $this->config = $config;
        $this->testCard = $card;
    }

    private function getPos()
    {
        return new GvpPos($this->config);
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