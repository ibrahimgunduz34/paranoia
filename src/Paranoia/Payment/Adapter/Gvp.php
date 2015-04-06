<?php
namespace Paranoia\Payment\Adapter;

use Paranoia\Common\Serializer\Serializer;
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

class Gvp extends AdapterAbstract
{
    /**
     * @var array
     */
    protected $transactionMap = array(
        self::TRANSACTION_TYPE_PREAUTHORIZATION  => 'preauth',
        self::TRANSACTION_TYPE_POSTAUTHORIZATION => 'postauth',
        self::TRANSACTION_TYPE_SALE              => 'sales',
        self::TRANSACTION_TYPE_CANCEL            => 'void',
        self::TRANSACTION_TYPE_REFUND            => 'refund',
    );

    /**
     * builds request base with common arguments.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @param string $transactionType
     *
     * @return array
     */
    private function buildBaseRequest(RequestInterface $request, $transactionType)
    {
        $terminal    = $this->buildTerminal($request, $transactionType);
        $customer    = $this->buildCustomer();
        $order       = $this->buildOrder($request);
        $transaction = $this->buildTransaction($request, $transactionType);
        return array(
            'Version'     => '0.01',
            'Mode'        => $this->configuration->getMode(),
            'Terminal'    => $terminal,
            'Order'       => $order,
            'Customer'    => $customer,
            'Transaction' => $transaction
        );
    }

    /**
     * builds terminal section of request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @param string $transactionType
     * @return array
     */
    private function buildTerminal(RequestInterface $request, $transactionType)
    {
        list($username, $password) = $this->getApiCredentialsByRequest($transactionType);
        $hash = $this->getTransactionHash($request, $password, $transactionType);
        return array(
            'ProvUserID' => $username,
            'HashData'   => $hash,
            'UserID'     => $username,
            'ID'         => $this->configuration->getTerminalId(),
            'MerchantID' => $this->configuration->getMerchantId()
        );
    }

    /**
     * builds customer section of request.
     *
     * @return array
     */
    private function buildCustomer()
    {
        /**
         * we don't want to share customer information
         * to bank.
         */
        return array(
            'IPAddress'    => '127.0.0.1',
            'EmailAddress' => 'dummy@dummy.net'
        );
    }

    /**
     * builds card section of request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @return array
     */
    private function buildCard(RequestInterface $request)
    {
        $expireMonth = $this->formatExpireDate(
            $request->getExpireMonth(),
            $request->getExpireYear()
        );
        return array(
            'Number'     => $request->getCardNumber(),
            'ExpireDate' => $expireMonth,
            'CVV2'       => $request->getSecurityCode()
        );
    }

    /**
     * builds order section of request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @return array
     */
    private function buildOrder(RequestInterface $request)
    {
        return array(
            'OrderID'     => $this->formatOrderId($request->getOrderId()),
            'GroupID'     => null,
            'Description' => null
        );
    }

    /**
     * builds terminal section of request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @param string $transactionType
     * @param integer $cardHolderPresentCode
     * @param string $originalRetrefNum
     *
     * @return array
     */
    private function buildTransaction(
        RequestInterface $request,
        $transactionType,
        $cardHolderPresentCode = 0,
        $originalRetrefNum = null
    ) {
        $installment     = $this->isInstallmentRequired($transactionType) ? $this->formatInstallment($request->getInstallment()) : null;
        $amount          = $this->isAmountRequired($transactionType) ? $this->formatAmount($request->getAmount()) : '1';
        $currency        = $this->isAmountRequired($transactionType) ? $this->formatCurrency($request->getCurrency()) : null;
        $type            = $this->getProviderTransactionType($transactionType);
        return array(
            'Type'                  => $type,
            'InstallmentCnt'        => $installment,
            'Amount'                => $amount,
            'CurrencyCode'          => $currency,
            'CardholderPresentCode' => $cardHolderPresentCode,
            'MotoInd'               => 'N',
            'OriginalRetrefNum'     => $originalRetrefNum
        );
    }

    /**
     * returns boolean true, when amount field is required
     * for request transaction type.
     *
     * @param string $transactionType
     *
     * @return boolean
     */
    private function isAmountRequired($transactionType)
    {
        return in_array(
            $transactionType,
            array(
                self::TRANSACTION_TYPE_SALE,
                self::TRANSACTION_TYPE_PREAUTHORIZATION,
                self::TRANSACTION_TYPE_POSTAUTHORIZATION,
            )
        );
    }

    /**
     * returns boolean true, when card number field is required
     * for request transaction type.
     *
     * @param string $transactionType
     *
     * @return boolean
     */
    private function isCardNumberRequired($transactionType)
    {
        return in_array(
            $transactionType,
            array(
                self::TRANSACTION_TYPE_SALE,
                self::TRANSACTION_TYPE_PREAUTHORIZATION,
            )
        );
    }

    private function isInstallmentRequired($transactionType)
    {
        return in_array(
            $transactionType,
            array(
                self::TRANSACTION_TYPE_SALE,
                self::TRANSACTION_TYPE_PREAUTHORIZATION,
            )
        );
    }

    /**
     * returns api credentials by transaction type of request.
     *
     * @param string $transactionType
     *
     * @return array
     */
    private function getApiCredentialsByRequest($transactionType)
    {
        $isAuth = in_array(
            $transactionType,
            array(
                self::TRANSACTION_TYPE_SALE,
                self::TRANSACTION_TYPE_PREAUTHORIZATION,
                self::TRANSACTION_TYPE_POSTAUTHORIZATION,
            )
        );
        if ($isAuth) {
            return array(
                $this->configuration->getAuthorizationUsername(),
                $this->configuration->getAuthorizationPassword()
            );
        } else {
            return array(
                $this->configuration->getRefundUsername(),
                $this->configuration->getRefundPassword()
            );
        }
    }

    /**
     * returns security hash for using in transaction hash.
     *
     * @param string $password
     *
     * @return string
     */
    private function getSecurityHash($password)
    {
        $tidPrefix  = str_repeat('0', 9 - strlen($this->configuration->getTerminalId()));
        $terminalId = sprintf('%s%s', $tidPrefix, $this->configuration->getTerminalId());
        return strtoupper(SHA1(sprintf('%s%s', $password, $terminalId)));
    }

    /**
     * returns transaction hash for using in transaction request.
     *
     * @param \Paranoia\Payment\Request\RequestInterface $request
     * @param string $password
     * @param string $transactionType
     *
     * @return string
     */
    private function getTransactionHash(RequestInterface $request, $password, $transactionType)
    {
        $orderId      = $this->formatOrderId($request->getOrderId());
        $terminalId   = $this->configuration->getTerminalId();
        $cardNumber   = $this->isCardNumberRequired($transactionType) ? $request->getCardNumber() : '';
        $amount       = $this->isAmountRequired($transactionType) ? $this->formatAmount($request->getAmount()) : '1';
        $securityData = $this->getSecurityHash($password);
        return strtoupper(
            sha1(
                sprintf(
                    '%s%s%s%s%s',
                    $orderId,
                    $terminalId,
                    $cardNumber,
                    $amount,
                    $securityData
                )
            )
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
            $rawRequest,
            array( 'root_name' => 'GVPSRequest' )
        );
        return array( 'data' => $xml );
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPreauthorizationRequest()
     */
    protected function buildPreAuthorizationRequest(PreAuthorizationRequest $request)
    {
        $requestData = array( 'Card' => $this->buildCard($request) );
        return array_merge($requestData, $this->buildBaseRequest($request, self::TRANSACTION_TYPE_PREAUTHORIZATION));
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPostAuthorizationRequest()
     */
    protected function buildPostAuthorizationRequest(PostAuthorizationRequest $request)
    {
        $requestData = $this->buildBaseRequest($request, self::TRANSACTION_TYPE_POSTAUTHORIZATION);
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildSaleRequest()
     */
    protected function buildSaleRequest(SaleRequest $request)
    {
        $requestData = array( 'Card' => $this->buildCard($request) );
        return array_merge($requestData, $this->buildBaseRequest($request, self::TRANSACTION_TYPE_SALE));
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildRefundRequest()
     */
    protected function buildRefundRequest(RefundRequest $request)
    {
        return $this->buildBaseRequest($request, self::TRANSACTION_TYPE_REFUND);
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildCancelRequest()
     */
    protected function buildCancelRequest(CancelRequest $request)
    {
        $requestData                = $this->buildBaseRequest($request, self::TRANSACTION_TYPE_CANCEL);
        $transactionId              = ($request->getTransactionId()) ? $request->getTransactionId() : null;
        $transaction                = $this->buildTransaction($request, self::TRANSACTION_TYPE_CANCEL, 0, $transactionId);
        $requestData['Transaction'] = $transaction;
        return $requestData;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param ResponseAbstract $responseInstance
     */
    private function parseErrorMessage(\SimpleXMLElement $xml, ResponseAbstract $responseInstance)
    {
        $errorMessages = array();
        if (property_exists($xml->Transaction->Response, 'ErrorMsg')) {
            $errorMessages[] = sprintf(
                'Error Message: %s',
                (string)$xml->Transaction->Response->ErrorMsg
            );
        }
        if (property_exists($xml->Transaction->Response, 'SysErrMsg')) {
            $errorMessages[] = sprintf(
                'System Error Message: %s',
                (string)$xml->Transaction->Response->SysErrMsg
            );
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
        $responseInstance->setIsSuccess('00' == (string)$xml->Transaction->Response->Code)
                         ->setCode((string)$xml->Transaction->ReasonCode);
        if (!$responseInstance->isSuccess()) {
            $this->parseErrorMessage($xml, $responseInstance);
        } else {

            if(method_exists($responseInstance, 'setOrderId')) {
                $responseInstance->setOrderId((string)$xml->Order->OrderID);
            }

            if(method_exists($responseInstance, 'setTransactionId')) {
                $responseInstance->setTransactionId((string)$xml->Transaction->RetrefNum);
            }

        }
        return $responseInstance;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::formatAmount()
     */
    protected function formatAmount($amount, $reverse = false)
    {
        if (!$reverse) {
            return number_format($amount, 2, '', '');
        } else {
            return (float)sprintf('%s.%s', substr($amount, 0, -2), substr($amount, -2));
        }
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::formatExpireDate()
     */
    protected function formatExpireDate($month, $year)
    {
        return sprintf('%02s%s', $month, substr($year, -2));
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
