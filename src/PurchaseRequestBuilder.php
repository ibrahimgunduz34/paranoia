<?php
namespace Paranoia;

use DOMNode;

class PurchaseRequestBuilder extends BaseRequestBuilder
{
    const  REQUEST_TYPE = "Auth";

    public function __construct(
        Configuration $configuration,
        DecimalFormatter $amountFormatter,
        IsoNumericCurrencyCodeFormatter $currencyFormatter,
        SingleDigitInstallmentFormatter $installmentFormatter,
        ExpireDateFormatter $expireDateFormatter
    ) {
        parent::__construct(
            self::REQUEST_TYPE,
            $configuration,
            $amountFormatter,
            $currencyFormatter,
            $installmentFormatter,
            $expireDateFormatter
        );
    }


    /**
     * @param string $orderId
     */
    public function withOrderId($orderId)
    {
        $this->createElement($this->getRoot(), 'OrderId', $orderId, true);
        return $this;
    }

    /**
     * @param float $amount
     */
    public function withAmount($amount)
    {
        $this->createElement(
            $this->getRoot(),
            'Total',
            $this->amountFormatter->format($amount),
            true
        );
        return $this;
    }

    /**
     * @param string $currency
     */
    public function withCurrency($currency)
    {
        //TODO: parameter type will be replaced with an enum which provided by a suitable library.
        $this->createElement(
            $this->getRoot(),
            'Currency',
            $this->currencyFormatter->format($currency),
            true
        );
        return $this;
    }

    /**
     * @param integer $installment
     * @return $this
     */
    public function withInstallment($installment)
    {
        $this->createElement(
            $this->getRoot(),
            'Taksit',
            $this->installmentFormatter->format($installment),
            true
        );
        return $this;
    }

    /**
     * @param string $cardNumber
     */
    public function withCardNumber($cardNumber)
    {
        $this->createElement(
            $this->getRoot(),
            'Number',
            $cardNumber,
            true
        );
        return $this;
    }

    /**
     * @param integer $month
     * @param integer $year
     */
    public function withExpires($month, $year)
    {
        $this->createElement(
            $this->getRoot(),
            'Expires',
            $this->expireDateFormatter->format([$month, $year]),
            true
        );
        return $this;
    }

    /**
     * @param string $cvv2Val
     */
    public function withCvv2Val($cvv2Val)
    {
        $this->createElement(
            $this->getRoot(),
            'Cvv2Val',
            $cvv2Val,
            true
        );
        return $this;
    }
}
