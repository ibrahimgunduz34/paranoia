<?php
namespace Paranoia\Pos;

use Paranoia\Pos\Est\CC5Request;
use Paranoia\Transaction\Request;

class Est extends PosAbstract
{
    /* Currency codes that expected by payment provider */
    const POS_CURRENCY_CODE_TRL = '949';
    const POS_CURRENCY_CODE_EUR = '978';
    const POS_CURRENCY_CODE_USD = '848';

    private $currencyMap = array(
        Constants::CURRENCY_CODE_TRL => self::POS_CURRENCY_CODE_TRL,
        Constants::CURRENCY_CODE_EUR => self::POS_CURRENCY_CODE_EUR,
        Constants::CURRENCY_CODE_USD => self::POS_CURRENCY_CODE_USD,
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

        $data->setType('Auth');
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
        // TODO: Implement buildCancelRequest() method.
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildRefundRequest(Request $request)
    {
        // TODO: Implement buildRefundRequest() method.
    }

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    protected function parseSaleResponse($rawRequest)
    {
        // TODO: Implement parseSaleResponse() method.
    }

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    protected function parseCancelResponse($rawResponse)
    {
        // TODO: Implement parseCancelResponse() method.
    }

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    protected function parseRefundResponse($rawResponse)
    {
        // TODO: Implement parseRefundResponse() method.
    }

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    protected function parsePreAuthorizationResponse($rawResponse)
    {
        // TODO: Implement parsePreAuthorizationResponse() method.
    }

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    protected function parsePostAuthorizationResponse($rawResponse)
    {
        // TODO: Implement parsePostAuthorizationResponse() method.
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function sale(Request $request)
    {
        // TODO: Implement sale() method.
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function cancel(Request $request)
    {
        // TODO: Implement cancel() method.
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    public function refund(Request $request)
    {
        // TODO: Implement refund() method.
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

    private function makeRequest($builder, $parser, Request $request)
    {
        $data = $this->{$builder}($request);
        $serializer = SerializerBuilder::create()->build();
        $rawRequest = $serializer->serialize($data, 'xml');
        $httpRequest = $this->client->post($this->getConfig()->getApiUrl());
        $httpRequest->setPostField('DATA', $rawRequest);
        try {
            $httpResponse = $httpRequest->send();
            return $this->{$parser}($httpResponse->getBody(true));
        } catch(\Guzzle\Http\Exception\CurlException $e) {
            throw new ConnectionError();
        }
    }
}