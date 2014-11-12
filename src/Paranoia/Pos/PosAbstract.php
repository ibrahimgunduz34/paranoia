<?php
namespace Paranoia\Pos;

use Paranoia\Exception\UnsupportedArgumentError;
use Paranoia\Transaction\Request;
use Paranoia\Configuration\ConfigurationInterface;

abstract class PosAbstract
{
    /* Currency codes that supported by paranoia */
    const CURRENCY_CODE_TRL = 'TRL';
    const CURRENCY_CODE_EUR = 'EUR';
    const CURRENCY_CODE_USD = 'USD';

    const TRANSACTION_TYPE_SALE = 'Sale';
    const TRANSACTION_TYPE_CANCEL = 'Cancel';
    const TRANSACTION_TYPE_REFUND = 'Refund';

    /**
     * @var array
     */
    protected $currencyMap = array();

    /**
     * @var array
     */
    protected $transactionTypeMap = array();

    /**
     * @var \Paranoia\Configuration\ConfigurationInterface;
     */
    protected $config;

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    abstract protected function buildSaleRequest(Request $request);

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    abstract protected function buildCancelRequest(Request $request);

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return mixed
     */
    abstract protected function buildRefundRequest(Request $request);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseSaleResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseCancelResponse($rawResponse);

    /**
     * @param mixed $rawResponse
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseRefundResponse($rawResponse);

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    abstract public function sale(Request $request);

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    abstract public function cancel(Request $request);

    /**
     * @param \Paranoia\Transaction\Request $request
     * @return \Paranoia\Transaction\Response
     */
    abstract public function refund(Request $request);

    /**
     * @param float $amount
     * @return string
     */
    protected function toConcatenatedAmount($amount)
    {
        return number_format($amount, 2, '', '');
    }

    /**
     * @param string $concatenatedAmount
     * @return float
     */
    protected function toAmount($concatenatedAmount)
    {
        return (float)sprintf('%s.%s', substr($concatenatedAmount, 0, -2), substr($concatenatedAmount, -2));
    }

    /**
     * @param $currency
     * @return null
     * @throws \Paranoia\Exception\UnsupportedArgumentError
     */
    protected function formatCurrency($currency)
    {
        if(!$currency || $currency == null) {
            return null;
        }

        if(!array_key_exists($currency, $this->currencyMap)) {
            throw new UnsupportedArgumentError(sprintf('Unsupported currency: %s', $currency));
        }

        return $this->currencyMap[$currency];
    }

    /**
     * @param string $type
     * @return string
     * @throws \Paranoia\Exception\UnsupportedArgumentError
     */
    protected function formatTransactionType($type)
    {
        if(!array_key_exists($type, $this->transactionTypeMap)) {
            throw new UnsupportedArgumentError(sprintf('Unsupported transaction type: %s', $type));
        }

        return $this->transactionTypeMap[$type];
    }

    /**
     * @param string $orderId
     * @return string
     */
    protected function formatOrderId($orderId)
    {
        return $orderId;
    }

    /**
     * @param int $installment
     * @return int|string
     */
    protected function formatInstallment($installment)
    {
        return (!is_numeric($installment) || intval($installment) <= 1) ? '' : $installment;
    }

    /**
     * @return \Paranoia\Configuration\ConfigurationInterface $config
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Paranoia\Configuration\ConfigurationInterface $config
     */
    public function __construct(ConfigurationInterface $config)
    {
        $this->config = $config;
    }
} 