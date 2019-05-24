<?php
namespace Paranoia\Nestpay;

use Paranoia\Core\Builder\AbstractBuilderFactory;
use Paranoia\Core\Builder\AbstractRequestBuilder;
use Paranoia\Core\Exception\NotImplementedError;
use Paranoia\Core\Formatter\DecimalFormatter;
use Paranoia\Core\Formatter\IsoNumericCurrencyCodeFormatter;
use Paranoia\Core\Formatter\SingleDigitInstallmentFormatter;
use Paranoia\Core\TransactionType;
use Paranoia\Nestpay\Builder\CancelRequestBuilder;
use Paranoia\Nestpay\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Nestpay\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Nestpay\Builder\RefundRequestBuilder;
use Paranoia\Nestpay\Builder\SaleRequestBuilder;

class NestPayBuilderFactory extends AbstractBuilderFactory
{
    /**
     * @param $transactionType
     * @return AbstractRequestBuilder
     * @throws NotImplementedError
     */
    public function createBuilder($transactionType)
    {
        switch ($transactionType) {
            case TransactionType::SALE:
                return new SaleRequestBuilder(
                    $this->configuration,
                    new IsoNumericCurrencyCodeFormatter(),
                    new DecimalFormatter(),
                    new SingleDigitInstallmentFormatter(),
                    new Formatter\ExpireDateFormatter()
                );
            case TransactionType::CANCEL:
                return new CancelRequestBuilder(
                    $this->configuration,
                    new IsoNumericCurrencyCodeFormatter(),
                    new DecimalFormatter(),
                    new SingleDigitInstallmentFormatter(),
                    new Formatter\ExpireDateFormatter()
                );
            case TransactionType::REFUND:
                return new RefundRequestBuilder(
                    $this->configuration,
                    new IsoNumericCurrencyCodeFormatter(),
                    new DecimalFormatter(),
                    new SingleDigitInstallmentFormatter(),
                    new Formatter\ExpireDateFormatter()
                );
            case TransactionType::PRE_AUTHORIZATION:
                return new PreAuthorizationRequestBuilder(
                    $this->configuration,
                    new IsoNumericCurrencyCodeFormatter(),
                    new DecimalFormatter(),
                    new SingleDigitInstallmentFormatter(),
                    new Formatter\ExpireDateFormatter()
                );
            case TransactionType::POST_AUTHORIZATION:
                return new PostAuthorizationRequestBuilder(
                    $this->configuration,
                    new IsoNumericCurrencyCodeFormatter(),
                    new DecimalFormatter(),
                    new SingleDigitInstallmentFormatter(),
                    new Formatter\ExpireDateFormatter()
                );
            default:
                throw new NotImplementedError('Not implemented transaction type: ' . $transactionType);
        }
    }
}
