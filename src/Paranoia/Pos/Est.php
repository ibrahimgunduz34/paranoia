<?php
namespace Paranoia\Pos;

use Exception;
use Guzzle\Http\Client;
use JMS\Serializer\SerializerBuilder;
use Paranoia\Payment\Exception\UnexpectedResponse;
use Paranoia\Pos\Est\CC5Request;
use Paranoia\Transaction\Request;
use Paranoia\Transaction\Response;
use SimpleXMLElement;

class Est extends PosAbstract
{
    /* Currency codes that expected by payment provider */
    const POS_CURRENCY_CODE_TRL = '949';
    const POS_CURRENCY_CODE_EUR = '978';
    const POS_CURRENCY_CODE_USD = '848';

    /* Transaction types that supported by provider */
    const POS_TRANSACTION_TYPE_SALE = 'Auth';
    const POS_TRANSACTION_TYPE_CANCEL = 'Void';
    const POS_TRANSACTION_TYPE_REFUND = 'Credit';

    /**
     * @var array
     */
    protected $currencyMap = array(
        self::CURRENCY_CODE_TRL => self::POS_CURRENCY_CODE_TRL,
        self::CURRENCY_CODE_EUR => self::POS_CURRENCY_CODE_EUR,
        self::CURRENCY_CODE_USD => self::POS_CURRENCY_CODE_USD,
    );

    /**
     * @var array
     */
    protected $transactionTypeMap = array(
        self::TRANSACTION_TYPE_SALE   => self::POS_TRANSACTION_TYPE_SALE,
        self::TRANSACTION_TYPE_CANCEL => self::POS_TRANSACTION_TYPE_CANCEL,
        self::TRANSACTION_TYPE_REFUND => self::POS_TRANSACTION_TYPE_REFUND
    );


    private function buildBaseRequest(Request $request)
    {
        $data = new CC5Request();
        $config = $this->getConfig();
        $data->setClientId($config->getClientId())
            ->setName($config->getName())
            ->setPassword($config->getPassword())
            ->setMode($config->getMode());
        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildSaleRequest(Request $request)
    {
        /** @var $data \Paranoia\Pos\Est\CC5Request */
        $data = $this->buildBaseRequest($request);

        $data->setType(self::POS_TRANSACTION_TYPE_SALE);
        $data->setOrderId($this->formatOrderId($request->getOrderId()));
        $data->setTotal($this->normalizeAmount($request->getAmount()));
        $data->setCurrency($this->formatCurrency($request->getCurrency()));
        $data->setTaksit($this->formatInstallment($request->getInstallment()));

        /**
         * @var $card \Paranoia\Transaction\Resource\Request\Card
         */
        $card = $request->getResource();
        $data->setNumber($card->getNumber());
        $data->setExpires($this->formatExpireDate($card->getExpireMonth(), $card->getExpireYear()));
        $data->setCvc2Val($card->getSecurityCode());

        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildCancelRequest(Request $request)
    {
        /** @var $data \Paranoia\Pos\Est\CC5Request */
        $data = $this->buildBaseRequest($request);

        $data->setType(self::POS_TRANSACTION_TYPE_CANCEL);
        if($request->getTransactionId()) {
            $data->setTransId($request->getTransactionId());
        } else {
            $data->setOrderId($request->getOrderId());
        }
        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildRefundRequest(Request $request)
    {
        /** @var $data \Paranoia\Pos\Est\CC5Request */
        $data = $this->buildBaseRequest($request);

        $data->setType(self::POS_TRANSACTION_TYPE_REFUND);
        $data->setOrderId($this->formatOrderId($request->getOrderId()));
        $data->setTotal($this->toConcatenatedAmount($request->getAmount()));
        $data->setCurrency($this->formatCurrency($request->getCurrency()));

        return $data;
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    protected function parseSaleResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse);
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    protected function parseCancelResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse);
    }

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    protected function parseRefundResponse($rawResponse)
    {
        return $this->parseResponse($rawResponse);
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function sale(Request $request)
    {
        return $this->makeRequest('buildSaleRequest', 'parseSaleResponse', $request);
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function cancel(Request $request)
    {
        return $this->makeRequest('buildCancelRequest', 'parseCancelResponse', $request);
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function refund(Request $request)
    {
        return $this->makeRequest('buildRefundRequest', 'parseRefundResponse', $request);
    }

    /**
     * @param int $month
     * @param int $year
     * @return string
     */
    private function formatExpireDate($month, $year)
    {
        return sprintf('%02s/20%s', $month, substr((string) $year, -2));
    }

    /**
     * @param function $builder
     * @param function $parser
     * @param \Paranoia\Transaction\Request $request
     * @return string
     * @throws ConnectionError
     */
    private function makeRequest($builder, $parser, Request $request)
    {
        $data = $this->{$builder}($request);
        $serializer = SerializerBuilder::create()->build();
        $rawRequest = $serializer->serialize($data, 'xml');
        $client = new Client();
        $httpRequest = $client->post($this->getConfig()->getApiUrl());
        $httpRequest->setPostField('DATA', $rawRequest);
        try {
            $httpResponse = $httpRequest->send();

            return $this->{$parser}($httpResponse->getBody(true));
        } catch(\Guzzle\Http\Exception\CurlException $e) {
            throw new ConnectionError($e->getMessage());
        }
    }

    /**
     * @param string $rawResponse
     * @return \Paranoia\Transaction\Response
     * @throws \Paranoia\Payment\Exception\UnexpectedResponse
     */
    protected function parseResponse($rawResponse)
    {
        try {
            $xml = new SimpleXMLElement($rawResponse);
        } catch (Exception $e) {
             $errorMessage = sprintf(
                 'Unexpected error message returned from the provider. Detail:%s, Response:%s',
                 $e->getMessage(), $rawResponse
             );
             throw new UnexpectedResponse($errorMessage);
        }
        $response = new Response();
        $response->setApproved($xml->Response == 'Approved');
        $response->setCode((string) $xml->ProcReturnCode);
        if(!$response->isApproved()) {
            $errorMessages = array();

            if (property_exists($xml, 'Error')) {
                $errorMessages[] = sprintf('Error: %s', (string)$xml->Error);
            }

            if (property_exists($xml, 'ErrMsg')) {
                $errorMessages[] = sprintf('Error Message: %s ', (string)$xml->ErrMsg);
            }

            if (property_exists($xml, 'Extra') && property_exists($xml->Extra, 'HOSTMSG')) {
                $errorMessages[] = sprintf('Host Message: %s', (string)$xml->Extra->HOSTMSG);
            }

            $errorMessage = implode(' ', $errorMessages);
            $response->setMessage($errorMessage);
        } else {
            $response->setTransactionId((string) $xml->TransId);
        }
        return $response;
    }
}