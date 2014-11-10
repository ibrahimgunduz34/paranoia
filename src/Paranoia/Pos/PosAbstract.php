<?php
namespace Paranoia\Pos;

use Paranoia\Exception\CurrencyError;
use Paranoia\Transaction\Request;

abstract class PosAbstract
{
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
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseSaleResponse($rawRequest);

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseCancelResponse($rawResponse);

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parseRefundResponse($rawResponse);

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parsePreAuthorizationResponse($rawResponse);

    /**
     * @param mixed $rawRequest
     * @return \Paranoia\Transaction\Response
     */
    abstract protected function parsePostAuthorizationResponse($rawResponse);

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


    protected function toConcatenatedAmount($amount)
    {
        return number_format($amount, 2, '', '');
    }

    protected function toAmount($concatenatedAmount)
    {
        return (float)sprintf('%s.%s', substr($concatenatedAmount, 0, -2), substr($concatenatedAmount, -2));
    }

    /**
     * @param $currency
     * @return null
     * @throws \Paranoia\Exception\CurrencyError
     */
    protected function formatCurrency($currency)
    {
        if(!$currency || $currency == null) {
            return null;
        }

        if(!array_key_exists($currency, $this->currencyMap)) {
            throw new CurrencyError(sprintf('Undefined currency: %s', $currency));
        }

        return $this->currencyMap[$currency];
    }
} 