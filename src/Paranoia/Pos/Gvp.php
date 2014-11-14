<?php
namespace Paranoia\Pos;


use Exception;
use Guzzle\Http\Client;
use JMS\Serializer\SerializerBuilder;
use Paranoia\Exception\ConnectionError;
use Paranoia\Payment\Exception\UnexpectedResponse;
use Paranoia\Pos\Gvp\Card;
use Paranoia\Pos\Gvp\Customer;
use Paranoia\Pos\Gvp\GVPSRequest;
use Paranoia\Pos\Gvp\Order;
use Paranoia\Pos\Gvp\Terminal;
use Paranoia\Pos\Gvp\Transaction;
use Paranoia\Transaction\Request;
use Paranoia\Transaction\Response;
use SimpleXMLElement;

class Gvp extends PosAbstract
{
    /* Currency codes that expected by payment provider */
    const POS_CURRENCY_CODE_TRL = '949';
    const POS_CURRENCY_CODE_EUR = '978';
    const POS_CURRENCY_CODE_USD = '848';

    /* Transaction types that supported by provider */
    const POS_TRANSACTION_TYPE_SALE = 'sales';
    const POS_TRANSACTION_TYPE_CANCEL = 'void';
    const POS_TRANSACTION_TYPE_REFUND = 'refund';

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

    /* API Version */
    const VERSION = '0.01';

    private function buildBaseRequest(Request $request, $type)
    {
        $terminal    = $this->buildTerminal($request, $type);
        $customer    = $this->buildCustomer();
        $order       = $this->buildOrder($request);
        $transaction = $this->buildTransaction($request, $type);

        $data = new GVPSRequest();
        $data->setTerminal($terminal)
            ->setCustomer($customer)
            ->setOrder($order)
            ->setTransaction($transaction);

        return $data;
    }

    /**
     * @param string $password
     * @return string
     */
    private function buildSecurityHash($password)
    {
        $formattedTerminalId = str_pad($this->getConfig()->getTerminalId(), 9, '0', STR_PAD_LEFT);
        return strtoupper(sha1(implode('', array($password, $formattedTerminalId))));
    }

    /**
     * @param Request $request
     * @param string $type
     * @param string $password
     * @return string
     */
    private function buildHash(Request $request, $type, $password)
    {
        return strtoupper(sha1(implode('', array(
            $this->formatOrderId($request->getOrderId()),
            $this->getConfig()->getTerminalId(),
            $this->isCardNumberRequired($type) ? $request->getResource()->getNumber() : '',
            $this->isAmountRequired($type) ? $this->toConcatenatedAmount($request->getAmount()) : 1,
            $this->buildSecurityHash($password)
        ))));
    }

    private function buildTerminal(Request $request, $type)
    {
        /** @var $config \Paranoia\Configuration\Gvp */
        $config = $this->getConfig();

        $terminal = new Terminal();
        list($username, $password) = $this->getApiCredentialsByRequest($type);
        $hash = $this->buildHash($request, $type, $password);
        $terminal->setId($config->getTerminalId())
            ->setMerchantId($config->getMerchantId())
            ->setUserId($username)
            ->setProvUserId($username)
            ->setHashData($hash);
        
        return $terminal;
    }

    private function buildCustomer()
    {
        $customer = new Customer();
        $customer->setEmailAddress('dummy@dummy.net')
            ->setIpAddress('127.0.0.1');
        return $customer;
    }

    private function buildOrder(Request $request)
    {
        $order = new Order();
        $order->setOrderId($this->formatOrderId($request->getOrderId()));
        return $order;
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int $cardHolderPresentCode
     * @return Transaction
     */
    private function buildTransaction(Request $request, $type, $cardHolderPresentCode = 0)
    {
        $transaction = new Transaction();
        $transaction->setType($type);
        if($request->getInstallment()) {
            $transaction->setInstallmentCnt($request->getInstallment());
        }
        $transaction->setAmount(
            $this->isAmountRequired($type) ? $this->toConcatenatedAmount($request->getAmount()) : 1
        );
        if($request->getCurrency()) {
            $transaction->setCurrencyCode($this->formatCurrency($request->getCurrency()));
        }
        if($request->getTransactionId()) {
            $transaction->setOriginalRetRefNum($request->getTransactionId());
        }
        $transaction->setMotoInd('N')
            ->setCardHolderPresentCode($cardHolderPresentCode);
        return $transaction;
    }

    /**
     * @param Request $request
     * @return Card
     */
    private function buildCard(Request $request)
    {
        /** @var $cardResource \Paranoia\Transaction\Resource\Request\Card */
        $cardResource = $request->getResource();

        $formattedDate = $this->formatExpireDate($cardResource->getExpireMonth(), $cardResource->getExpireYear());
        $card = new Card();
        $card->setNumber($cardResource->getNumber())
            ->setCvv2($cardResource->getSecurityCode())
            ->setExpireDate($formattedDate);
        return $card;
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isAmountRequired($type)
    {
        return in_array($type, array(
            self::POS_TRANSACTION_TYPE_SALE,
            #TODO: Will be added preauth and postauth types when implemented.
        ));
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isTypeAuth($type)
    {
        return in_array($type, array(
            self::POS_TRANSACTION_TYPE_SALE,
            #TODO: Will be added preauth and postauth types when implemented.
        ));
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isCardNumberRequired($type)
    {
        return in_array($type, array(
            self::POS_TRANSACTION_TYPE_SALE,
            #TODO: Will be added preauth and postauth types when implemented.
        ));
    }

    /**
     * @param $type
     */
    private function getApiCredentialsByRequest($type)
    {
        /** @var $config \Paranoia\Configuration\Gvp */
        $config = $this->getConfig();

        if($this->isTypeAuth($type)) {
            return array($config->getAuthUsername(), $config->getAuthPassword());
        } else {
            return array($config->getRefundUsername(), $config->getRefundPassword());
        }
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildSaleRequest(Request $request)
    {
        return $this->buildBaseRequest($request, self::POS_TRANSACTION_TYPE_SALE)
            ->setCard($this->buildCard($request));

    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildCancelRequest(Request $request)
    {
        return $this->buildBaseRequest($request, self::POS_TRANSACTION_TYPE_CANCEL);
    }

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    protected function buildRefundRequest(Request $request)
    {
        return $this->buildBaseRequest($request, self::POS_TRANSACTION_TYPE_REFUND);
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
        return sprintf('%02s%s', $month, substr($year, -2));
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
        $httpRequest = $client->post($this->getConfig()->getApiUrl(), null, null, array('verify' => false));
    print $rawRequest;

        $httpRequest->setPostField('data', $rawRequest);
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
            print $rawResponse;
            $xml = new SimpleXMLElement($rawResponse);
        } catch (Exception $e) {
            $errorMessage = sprintf(
                'Unexpected error message returned from the provider. Detail:%s, Response:%s',
                $e->getMessage(), $rawResponse
            );
            throw new UnexpectedResponse($errorMessage);
        }
        $response = new Response();
        $response->setApproved((string)$xml->Transaction->Response->Code == '00');
        $response->setCode((string)$xml->Transaction->ReasonCode);
        if(!$response->isApproved()) {
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
            $response->setMessage($errorMessage);
        } else {
            $response->setOrderId((string)$xml->Order->OrderID);
            $response->setTransactionId((string)$xml->Transaction->RetrefNum);
        }
        return $response;
    }
}