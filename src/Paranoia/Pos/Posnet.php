<?php
namespace Paranoia\Pos;

use Exception;
use Paranoia\Pos\Posnet\PosnetRequest;
use Paranoia\Pos\Posnet\ReturnA;
use Paranoia\Pos\Posnet\Reverse;
use Paranoia\Pos\Posnet\Sale;
use Paranoia\Transaction\Request;
use Paranoia\Transaction\Response;

class Posnet extends PosAbstract
{
    /* Currency codes that expected by payment provider */
    const POS_CURRENCY_CODE_TRL = 'YT';
    const POS_CURRENCY_CODE_EUR = 'EU';
    const POS_CURRENCY_CODE_USD = 'US';

    /* Transaction types that supported by provider */
    const POS_TRANSACTION_TYPE_SALE = 'sale';
    const POS_TRANSACTION_TYPE_CANCEL = 'reverse';
    const POS_TRANSACTION_TYPE_REFUND = 'return';

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
        /** @var $config \Paranoia\Configuration\Posnet */
        $config = $this->getConfig();

        $data = new PosnetRequest();
        $data->setMid($config->getMid())
            ->setTid($config->getTid())
            ->setUsername($config->getUsername())
            ->setPassword($config->getPassword());
        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildSaleRequest(Request $request)
    {
        $data = $this->buildBaseRequest($request);

        /** @var $card \Paranoia\Transaction\Resource\Request\Card */
        $card = $request->getResource();

        $sale = new Sale();
        $sale->setAmount($request->getAmount())
            ->setCurrencyCode($this->formatCurrency($request->getCurrency()))
            ->setOrderId($this->formatOrderId($request->getOrderId()))
            ->setCcno($card->getNumber())
            ->setCvc($card->getSecurityCode())
            ->setExpDate($this->formatExpireDate($card->getExpireMonth(), $card->getExpireYear()))
            ->setInstallment($request->getInstallment())
            ->setExtraPoint('000000')
            ->setMultiplePoint('000000');

        $data->setSale($sale);
        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildCancelRequest(Request $request)
    {
        $data = $this->buildBaseRequest($request);
        $reverse = new Reverse();
        if($request->getTransactionId()) {
            $reverse->setHostLogKey($request->getTransactionId());
        } else {
            $reverse->setOrderId($this->formatOrderId($request->getOrderId()));
        }
        #Bank expects transaction type to be cancelled.
        $reverse->setTransaction(self::POS_TRANSACTION_TYPE_SALE);
        $data->setReverse($reverse);
        return $data;
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildRefundRequest(Request $request)
    {
        $data = $this->buildBaseRequest($request);
        $rtn = new ReturnA();
        if($request->getTransactionId()) {
            $rtn->setHostLogKey($request->getTransactionId());
        } else {
            $rtn->setOrderId($this->formatOrderId($request->getOrderId()));
        }
        $rtn->setAmount($request->getAmount())
            ->setCurrencyCode($this->formatCurrency($request->getCurrency()));
        $data->setReturn($rtn);
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

    private function parseResponse($rawResponse)
    {
        try {
            $xml = new SimpleXMLElement($rawResponse);
        } catch(Exception $e) {
            $errorMessage = sprintf(
                'Unexpected error message returned from the provider. Detail:%s, Response:%s',
                $e->getMessage(), $rawResponse
            );
            throw new UnexpectedResponse($errorMessage);
        }
        $response = new Response();
        $response->setApproved((int)$xml->approved > 0);
        if(!$response->isApproved()) {
            $response->setCode((string)$xml->respCode);
            $errorMessages = array();
            if (property_exists($xml, 'respCode')) {
                $errorMessages[] = sprintf('Error: %s', (string)$xml->respCode);
            }
            if (property_exists($xml, 'respText')) {
                $errorMessages[] = sprintf('Error Message: %s ', (string)$xml->respText);
            }
            $errorMessage = implode(' ', $errorMessages);
            $response->setMessage($errorMessage);
        } else {
            $response->setCode('00');
            $response->setTransactionId((string)$xml->hostlogkey);
        }
        return $response;
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
        return sprintf('%02s%02s', substr($year, -2), $month);
    }

    /**
     * @param int $installment
     * @return int|string
     */
    protected function formatInstallment($installment)
    {
        if (!is_numeric($installment) || intval($installment) <= 1) {
            return '00';
        }
        return sprintf('%02s', $installment);
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
        $httpRequest->setPostField('xmldata', $rawRequest);
        try {
            $httpResponse = $httpRequest->send();

            return $this->{$parser}($httpResponse->getBody(true));
        } catch(\Guzzle\Http\Exception\CurlException $e) {
            throw new ConnectionError($e->getMessage());
        }
    }
}