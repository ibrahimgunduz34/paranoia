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
use Paranoia\Payment\Exception\UnexpectedResponse;
use Paranoia\Payment\Response\PostAuthorizationResponse;
use Paranoia\Payment\Response\PreAuthorizationResponse;
use Paranoia\Payment\Response\RefundResponse;
use Paranoia\Payment\Response\ResponseAbstract;
use Paranoia\Payment\Response\SaleResponse;

class NestPay extends AdapterAbstract
{
    /**
     * @var array
     */
    protected $transactionMap = array(
        self::TRANSACTION_TYPE_PREAUTHORIZATION  => 'PreAuth',
        self::TRANSACTION_TYPE_POSTAUTHORIZATION => 'PostAuth',
        self::TRANSACTION_TYPE_SALE              => 'Auth',
        self::TRANSACTION_TYPE_CANCEL            => 'Void',
        self::TRANSACTION_TYPE_REFUND            => 'Credit',
    );

    /**
     * builds request base with common arguments.
     *
     * @return array
     */
    private function buildBaseRequest()
    {
        return array(
            'Name'     => $this->configuration->getUsername(),
            'Password' => $this->configuration->getPassword(),
            'ClientId' => $this->configuration->getClientId(),
            'Mode'     => $this->configuration->getMode()
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
            array_merge($rawRequest, $this->buildBaseRequest()),
            array( 'root_name' => 'CC5Request' )
        );
        return array( 'DATA' => $xml );
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPreauthorizationRequest()
     */
    protected function buildPreAuthorizationRequest(PreAuthorizationRequest $request)
    {
        $amount      = $this->formatAmount($request->getAmount());
        $installment = $this->formatInstallment($request->getInstallment());
        $currency    = $this->formatCurrency($request->getCurrency());
        $expireMonth = $this->formatExpireDate($request->getExpireMonth(), $request->getExpireYear());
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_PREAUTHORIZATION);
        $requestData = array(
            'Type'     => $type,
            'Total'    => $amount,
            'Currency' => $currency,
            'Taksit'   => $installment,
            'Number'   => $request->getCardNumber(),
            'Cvv2Val'  => $request->getSecurityCode(),
            'Expires'  => $expireMonth,
            'OrderId'  => $this->formatOrderId($request->getOrderId()),
        );
        return $requestData;
    }

    /**
     * {@inheritdoc}
     * @see Paranoia\Payment\Adapter\AdapterAbstract::buildPostAuthorizationRequest()
     */
    protected function buildPostAuthorizationRequest(PostAuthorizationRequest $request)
    {
        $type        = $this->getProviderTransactionType(self::TRANSACTION_TYPE_POSTAUTHORIZATION);
        $requestData = array(
            'Type'    => $type,
            'OrderId' => $this->formatOrderId($request->getOrderId()),
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
            'Type'     => $type,
            'Total'    => $amount,
            'Currency' => $currency,
            'Taksit'   => $installment,
            'Number'   => $request->getCardNumber(),
            'Cvv2Val'  => $request->getSecurityCode(),
            'Expires'  => $expireMonth,
            'OrderId'  => $this->formatOrderId($request->getOrderId()),
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
            'Type'     => $type,
            'Total'    => $amount,
            'Currency' => $currency,
            'OrderId'  => $this->formatOrderId($request->getOrderId()),
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
            'Type'    => $type,
            'OrderId' => $this->formatOrderId($request->getOrderId()),
        );
        if ($request->getTransactionId()) {
            $requestData['TransId'] = $request->getTransactionId();
        }
        return $requestData;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param ResponseAbstract $responseInstance
     */
    private function parseErrorMessage(\SimpleXMLElement $xml, ResponseAbstract $responseInstance)
    {
        $errorMessages = array();
        if (property_exists($xml, 'Error')) {
            $errorMessages[] = sprintf('Error: %s', (string)$xml->Error);
        }
        if (property_exists($xml, 'ErrMsg')) {
            $errorMessages[] = sprintf(
                'Error Message: %s ',
                (string)$xml->ErrMsg
            );
        }
        if (property_exists($xml, 'Extra') && property_exists($xml->Extra, 'HOSTMSG')) {
            $errorMessages[] = sprintf(
                'Host Message: %s',
                (string)$xml->Extra->HOSTMSG
            );
        }
        $errorMessage = implode(' ', $errorMessages);
        $responseInstance->setMessage($errorMessage);
    }

    /**
     * @param $rawResponse
     * @param ResponseAbstract $responseInstance
     * @return ResponseAbstract
     * @throws \Paranoia\Payment\Exception\UnexpectedResponse
     */
    private function parseResponse($rawResponse, ResponseAbstract $responseInstance)
    {
        try {
            /** @var $xml \SimpleXMLElement */
            $xml = new \SimpleXmlElement($rawResponse);
        } catch(\Exception $e) {
            throw new UnexpectedResponse('Provider returned unexpected response: ' . $rawResponse);
        }

        $responseInstance->setIsSuccess((string)$xml->Response == 'Approved')
                         ->setCode((string)$xml->ProcReturnCode);

        if(!$responseInstance->isSuccess()) {
            $this->parseErrorMessage($xml, $responseInstance);
        } else {

            if(method_exists($responseInstance, 'setOrderId')) {
                $responseInstance->setOrderId((string)$xml->OrderId);
            }

            if(method_exists($responseInstance, 'setTransactionId')) {
                $responseInstance->setTransactionId((string)$xml->TransId);
            }

        }
        return $responseInstance;
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
