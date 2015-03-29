<?php
namespace Paranoia\Payment\Adapter;

use Guzzle\Http\Client as HttpClient;
use Paranoia\Configuration\AbstractConfiguration;
use Paranoia\Payment\Exception\CommunicationError;
use Paranoia\Payment\Request\CancelRequest;
use Paranoia\Payment\Request\PostAuthorizationRequest;
use Paranoia\Payment\Request\PreAuthorizationRequest;
use Paranoia\Payment\Request\RefundRequest;
use Paranoia\Payment\Request\RequestInterface;
use Paranoia\Payment\Request\SaleRequest;
use Paranoia\Payment\Response;
use Paranoia\Payment\Exception\UnknownTransactionType;
use Paranoia\Payment\Exception\UnknownCurrencyCode;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Guzzle\Http\Exception\RequestException;

abstract class AdapterAbstract
{
    /* Currency Types */
    const CURRENCY_TRY = 'TRY';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    /* Events */
    const EVENT_ON_TRANSACTION_SUCCESSFUL = 'OnTransactionSuccessful';
    const EVENT_ON_TRANSACTION_FAILED = 'OnTransactionFailed';
    const EVENT_ON_EXCEPTION = 'OnException';

    /* Transaction Types*/
    const TRANSACTION_TYPE_PREAUTHORIZATION = 'preAuthorization';
    const TRANSACTION_TYPE_POSTAUTHORIZATION = 'postAuthorization';
    const TRANSACTION_TYPE_SALE = 'sale';
    const TRANSACTION_TYPE_CANCEL = 'cancel';
    const TRANSACTION_TYPE_REFUND = 'refund';

    /**
     * @var array
     */
    protected $currencyCodes = array(
        self::CURRENCY_TRY => 949,
        self::CURRENCY_EUR => 840,
        self::CURRENCY_USD => 978,
    );

    /**
     * @var array
     */
    protected $transactionMap = array();

    /**
     * @var AbstractConfiguration
     */
    protected $configuration;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    public function __construct(AbstractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param \Paranoia\Configuration\AbstractConfiguration $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return EventDispatcher
     */
    protected function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }
        return $this->dispatcher;
    }

    /**
     * build request data for preauthorization transaction.
     *
     * @param \Paranoia\Payment\Request\PreAuthorizationRequest $request
     *
     * @return mixed
     */
    abstract protected function buildPreAuthorizationRequest(PreAuthorizationRequest $request);

    /**
     * @param \Paranoia\Payment\Request\PostAuthorizationRequest $request
     *
     * @return mixed
     */
    abstract protected function buildPostAuthorizationRequest(PostAuthorizationRequest $request);

    /**
     * build request data for sale transaction.
     *
     * @param \Paranoia\Payment\Request\SaleRequest $request
     *
     * @return mixed
     */
    abstract protected function buildSaleRequest(SaleRequest $request);

    /**
     * build request data for refund transaction.
     *
     * @param \Paranoia\Payment\Request\RefundRequest $request
     *
     * @return mixed
     */
    abstract protected function buildRefundRequest(RefundRequest $request);

    /**
     * build request data for cancel transaction.
     *
     * @param \Paranoia\Payment\Request\CancelRequest $request
     *
     * @return mixed
     */
    abstract protected function buildCancelRequest(CancelRequest $request);

    /**
     *  build complete raw data for the specified request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @param string $requestBuilder
     *
     * @return mixed
     */
    abstract protected function buildRequest(RequestInterface $request, $requestBuilder);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\PreAuthorizationResponse
     */
    abstract protected function parsePreAuthorizationResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\PostAuthorizationResponse
     */
    abstract protected function parsePostAuthorizationResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\SaleResponse
     */
    abstract protected function parseSaleResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\RefundResponse
     */
    abstract protected function parseRefundResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Payment\Response\CancelResponse
     */
    abstract protected function parseCancelResponse($rawResponse);


    /**
     * Makes http request to remote host.
     *
     * @param string $url
     * @param mixed  $data
     * @param array $options
     *
     * @throws \ErrorException|\Exception
     * @return mixed
     */
    protected function sendRequest($url, $data, $options = null)
    {
        $client = new HttpClient();
        $client->setConfig(array(
           'curl.options' => array(
               CURLOPT_SSL_VERIFYPEER => false,
               CURLOPT_SSL_VERIFYHOST => false,
           )
        ));
        $request = $client->post($url, null, $data);
        try {
            return $request->send()->getBody();
        } catch (RequestException $e) {
            throw new CommunicationError('Communication failed: ' . $url);
        }

    }

    /**
     * formats the specified string currency code by iso currency codes.
     *
     * @param string $currency
     *
     * @throws \Paranoia\Payment\Exception\UnknownCurrencyCode
     * @return integer
     */
    protected function formatCurrency($currency)
    {
        if (!isset($this->currencyCodes[$currency])) {
            throw new UnknownCurrencyCode(sprintf('%s is unknown currency.', $currency));
        }
        return $this->currencyCodes[$currency];
    }

    /**
     * returns formatted amount with doth or without doth.
     * formatted number returns amount default without doth.
     *
     * @param string|float $amount
     * @param boolean      $reverse
     *
     * @return string
     */
    protected function formatAmount($amount, $reverse = false)
    {
        if (!$reverse) {
            return number_format($amount, 2, '.', '');
        } else {
            return (float)sprintf('%s.%s', substr($amount, 0, -2), substr($amount, -2));
        }
    }

    /**
     * formats expire date as month/year
     *
     * @param int $month
     * @param int $year
     *
     * @return string
     */
    protected function formatExpireDate($month, $year)
    {
        return sprintf('%02s/%04s', $month, $year);
    }

    /**
     * returns formatted installment amount
     *
     * @param int $installment
     *
     * @return string
     */
    protected function formatInstallment($installment)
    {
        return (!is_numeric($installment) || intval($installment) <= 1) ? '' : $installment;
    }

    /**
     * returns formatted order number.
     *
     * @param $orderId
     *
     * @return mixed
     */
    protected function formatOrderId($orderId)
    {
        return $orderId;
    }

    /**
     * returns transaction code by expected provider.
     *
     * @param string $transactionType
     *
     * @throws \Paranoia\Payment\Exception\UnknownTransactionType
     * @return string
     */
    protected function getProviderTransactionType($transactionType)
    {
        if (!array_key_exists($transactionType, $this->transactionMap)) {
            throw new UnknownTransactionType('Transaction type is unknown: ' . $transactionType);
        }
        return $this->transactionMap[$transactionType];
    }

    /**
     * @param PreAuthorizationRequest $request
     *
     * @return \Paranoia\Payment\Response\PaymentResponse
     */
    public function preAuthorization(PreAuthorizationRequest $request)
    {
        $rawRequest  = $this->buildRequest($request, 'buildPreauthorizationRequest');
        $rawResponse = $this->sendRequest($this->configuration->getApiUrl(), $rawRequest);
        $response    = $this->parseResponse($rawResponse, self::TRANSACTION_TYPE_PREAUTHORIZATION);
        return $response;
    }

    /**
     * @param \Paranoia\Payment\Request\PostAuthorizationRequest $request
     *
     * @return \Paranoia\Payment\Response\PaymentResponse
     */
    public function postAuthorization(PostAuthorizationRequest $request)
    {
        $rawRequest  = $this->buildRequest($request, 'buildPostAuthorizationRequest');
        $rawResponse = $this->sendRequest($this->configuration->getApiUrl(), $rawRequest);
        $response    = $this->parseResponse($rawResponse, self::TRANSACTION_TYPE_POSTAUTHORIZATION);
        return $response;
    }

    /**
     * @param \Paranoia\Payment\Request\SaleRequest $request
     *
     * @return \Paranoia\Payment\Response\PaymentResponse
     */
    public function sale(SaleRequest $request)
    {
        $rawRequest  = $this->buildRequest($request, 'buildSaleRequest');
        $rawResponse = $this->sendRequest($this->configuration->getApiUrl(), $rawRequest);
        $response    = $this->parseResponse($rawResponse, self::TRANSACTION_TYPE_SALE);
        return $response;
    }

    /**
     * @param \Paranoia\Payment\Request\RefundRequest $request
     *
     * @return \Paranoia\Payment\Response\PaymentResponse
     */
    public function refund(RefundRequest $request)
    {
        $rawRequest  = $this->buildRequest($request, 'buildRefundRequest');
        $rawResponse = $this->sendRequest($this->configuration->getApiUrl(), $rawRequest);
        $response    = $this->parseResponse($rawResponse, self::TRANSACTION_TYPE_REFUND);
        return $response;
    }

    /**
     * @param \Paranoia\Payment\Request\CancelRequest $request
     *
     * @return \Paranoia\Payment\Response\PaymentResponse
     */
    public function cancel(CancelRequest $request)
    {
        $rawRequest  = $this->buildRequest($request, 'buildCancelRequest');
        $rawResponse = $this->sendRequest($this->configuration->getApiUrl(), $rawRequest);
        $response    = $this->parseResponse($rawResponse, self::TRANSACTION_TYPE_CANCEL);
        return $response;
    }

    /**
     * mask some critical information in transaction request.
     *
     * @param string $rawRequest
     *
     * @return string
     */
    protected function maskRequest($rawRequest)
    {
        return $rawRequest;
    }
}
