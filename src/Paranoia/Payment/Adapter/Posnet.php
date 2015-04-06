<?php
namespace Paranoia\Payment\Adapter;

use Paranoia\Common\Serializer\Serializer;
use Paranoia\Payment\PaymentEventArg;
use Paranoia\Payment\Request\CancelRequest;
use Paranoia\Payment\Request\PostAuthorizationRequest;
use Paranoia\Payment\Request\PreAuthorizationRequest;
use Paranoia\Payment\Request\RefundRequest;
use Paranoia\Payment\Request\RequestInterface;
use Paranoia\Payment\Request\SaleRequest;
use Paranoia\Payment\Response\CancelResponse;
use Paranoia\Payment\Response\PostAuthorizationResponse;
use Paranoia\Payment\Response\PreAuthorizationResponse;
use Paranoia\Payment\Response\RefundResponse;
use Paranoia\Payment\Response\ResponseAbstract;
use Paranoia\Payment\Response\SaleResponse;
use Paranoia\Payment\Exception\UnexpectedResponse;

class Posnet extends AdapterAbstract
{
    /**
     * @var array
     */
    protected $currencyCodes = array(
        self::CURRENCY_TRY => 'YT',
        self::CURRENCY_EUR => 'EU',
        self::CURRENCY_USD => 'US',
    );

    /**
     * @var array
     */
    protected $transactionMap = array(
        self::TRANSACTION_TYPE_PREAUTHORIZATION  => 'auth',
        self::TRANSACTION_TYPE_POSTAUTHORIZATION => 'capt',
        self::TRANSACTION_TYPE_SALE              => 'sale',
        self::TRANSACTION_TYPE_CANCEL            => 'reverse',
        self::TRANSACTION_TYPE_REFUND            => 'return',
    );

    /**
     * builds request base with common arguments.
     *
     * @return array
     */
    private function buildBaseRequest()
    {
        return array(
            'mid'      => $this->configuration->getMerchantId(),
            'tid'      => $this->configuration->getTerminalId()
        );
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildRequest()
     */
    protected function buildRequest(RequestInterface $request, $requestBuilder)
    {
        $rawRequest = call_user_func(array( $this, $requestBuilder ), $request);
        $serializer = new Serializer(Serializer::XML);
        $xml        = $serializer->serialize(
            array_merge($this->buildBaseRequest(), $rawRequest),
            array( 'root_name' => 'posnetRequest' )
        );
        return array( 'xmldata' => $xml );
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPreauthorizationRequest()
     */
    protected function buildPreauthorizationRequest(PreAuthorizationRequest $request)
    {
        $amount      = $this->formatAmount($request->getAmount());
        $installment = $this->formatInstallment($request->getInstallment());
        $currency    = $this->formatCurrency($request->getCurrency());
        $expireMonth = $this->formatExpireDate($request->getExpireMonth(), $request->getExpireYear());
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_PREAUTHORIZATION);
        $requestData = array(
            $type => array(
                'ccno'          => $request->getCardNumber(),
                'expDate'       => $expireMonth,
                'cvc'           => $request->getSecurityCode(),
                'amount'        => $amount,
                'currencyCode'  => $currency,
                'orderID'       => $this->formatOrderId($request->getOrderId()),
                'installment'   => $installment,
                #TODO: this fields will be used, when point and some bank benefit usage is implemented.
                // 'extraPoint'    => "000000",
                // 'multiplePoint' => "000000"
            )
        );
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPostAuthorizationRequest()
     */
    protected function buildPostAuthorizationRequest(PostAuthorizationRequest $request)
    {
        $amount      = $this->formatAmount($request->getAmount());
        $installment = $this->formatInstallment($request->getInstallment());
        $currency    = $this->formatCurrency($request->getCurrency());
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_POSTAUTHORIZATION);
        $requestData = array(
            $type => array(
                'hostLogKey'    => $request->getTransactionId(),
                'authCode'      => $request->getAuthCode(),
                'amount'        => $amount,
                'currencyCode'  => $currency,
                'installment'   => $installment,
                #TODO: this fields will be used, when point and some bank benefit usage is implemented.
                // 'extraPoint'    => "000000",
                // 'multiplePoint' => "000000"
            )
        );
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildSaleRequest()
     */
    protected function buildSaleRequest(SaleRequest $request)
    {
        $amount      = $this->formatAmount($request->getAmount());
        $installment = $this->formatInstallment($request->getInstallment());
        $currency    = $this->formatCurrency($request->getCurrency());
        $expireMonth = $this->formatExpireDate($request->getExpireMonth(), $request->getExpireYear());
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_SALE);
        $requestData = array(
            $type => array(
                'ccno'          => $request->getCardNumber(),
                'expDate'       => $expireMonth,
                'cvc'           => $request->getSecurityCode(),
                'amount'        => $amount,
                'currencyCode'  => $currency,
                'orderID'       => $this->formatOrderId($request->getOrderId()),
                'installment'   => $installment,
                #TODO: this fields will be used, when point and some bank benefit usage is implemented.
                // 'extraPoint'    => "000000",
                // 'multiplePoint' => "000000"
            )
        );
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildRefundRequest()
     */
    protected function buildRefundRequest(RefundRequest $request)
    {
        $amount      = $this->formatAmount($request->getAmount());
        $currency    = $this->formatCurrency($request->getCurrency());
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_REFUND);
        $requestData = array(
            $type => array(
                'hostLogKey'   => $request->getTransactionId(),
                'amount'       => $amount,
                'currencyCode' => $currency
            )
        );
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildCancelRequest()
     */
    protected function buildCancelRequest(CancelRequest $request)
    {
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_CANCEL);
        $requestData = array(
            $type => array(
                'transaction' => "sale",
                'hostLogKey'  => $request->getTransactionId(),
                'authCode'    => $request->getAuthCode()
            )
        );
        return $requestData;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param ResponseAbstract $responseInstance
     */
    private function parseErrorMessage(\SimpleXMLElement $xml, ResponseAbstract $responseInstance)
    {
        $responseInstance->setCode((string)$xml->respCode);
        $errorMessages = array();
        if (property_exists($xml, 'respCode')) {
            $errorMessages[] = sprintf('Error: %s', (string)$xml->respCode);
        }
        if (property_exists($xml, 'respText')) {
            $errorMessages[] = sprintf('Error Message: %s ', (string)$xml->respText);
        }
        $errorMessage = implode(' ', $errorMessages);
        $responseInstance->setMessage($errorMessage);
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::parseResponse()
     */
    private function parseResponse($rawResponse, ResponseAbstract $responseInstance)
    {
        try {
            /** @var $xml \SimpleXMLElement */
            $xml = new \SimpleXmlElement($rawResponse);
        } catch ( \Exception $e ) {
            throw new UnexpectedResponse('Provider returned unexpected response: ' . $rawResponse);
        }
        $responseInstance->setIsSuccess((int)$xml->approved > 0);
        if (!$responseInstance->isSuccess()) {
            $this->parseErrorMessage($xml, $responseInstance);
        } else {

            if(method_exists($responseInstance, 'setOrderId') && property_exists($xml, 'orderId')) {
                $responseInstance->setOrderId((string) $xml->orderId);
            }

            if(method_exists($responseInstance, 'setTransactionId') &&
                    property_exists($xml, 'hostlogkey') &&
                    property_exists($xml, 'authCode')) {
                $transactionId = sprintf('%s_%s', (string) $xml->hostlogkey, (string) (string)$xml->authCode);
                $responseInstance->setTransactionId($transactionId);
            }

        }

        return $responseInstance;
    }

    /**
     * {@inheritdoc}
     * Posnet tutar değerinde nokta istemiyor. Örnek:15.00TL için 1500 gönderilmesi gerekiyor.
     *
     * @see Paranoia\Payment\Adapter\AdapterAbstract::formatAmount()
     */
    protected function formatAmount($amount, $reverse = false)
    {
        if (!$reverse) {
            return ceil($amount * 100);
        } else {
            return (float)sprintf('%s.%s', substr($amount, 0, -2), substr($amount, -2));
        }
    }

    /**
     * {@inheritdoc}
     * Posnet Son Kullanma Tarihini YYMM formatında istiyor. Örnek:03/2014 için 1403
     *
     * @see Paranoia\Payment\Adapter\AdapterAbstract::formatExpireDate()
     */
    protected function formatExpireDate($month, $year)
    {
        return sprintf('%02s%02s', substr($year, -2), $month);
    }

    /**
     * {@inheritdoc}
     * Postnet Taksit sayısında daima 2 rakam gönderilmesini istiyor.
     *
     * @see Paranoia\Payment\Adapter\AdapterAbstract::formatInstallment()
     */
    protected function formatInstallment($installment)
    {
        if (!is_numeric($installment) || intval($installment) <= 1) {
            return '00';
        }
        return sprintf('%02s', $installment);
    }

    /**
     * @param $orderId
     * @return mixed|string
     */
    protected function formatOrderId($orderId)
    {
        return str_repeat('0', 24 - strlen($orderId)) . $orderId;
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\PreAuthorizationResponse
     */
    protected function parsePreAuthorizationResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse, new PreAuthorizationResponse());
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\PostAuthorizationResponse
     */
    protected function parsePostAuthorizationResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse, new PostAuthorizationResponse());
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\SaleResponse
     */
    protected function parseSaleResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse, new SaleResponse());
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\RefundResponse
     */
    protected function parseRefundResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse, new RefundResponse());
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\CancelResponse
     */
    protected function parseCancelResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse, new CancelResponse());
    }
}
