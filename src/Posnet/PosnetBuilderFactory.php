<?php
namespace Paranoia\Posnet;

use Paranoia\Core\Builder\AbstractBuilderFactory;
use Paranoia\Core\Builder\AbstractRequestBuilder;
use Paranoia\Core\Exception\NotImplementedError;
use Paranoia\Core\Formatter\MoneyFormatter;
use Paranoia\Core\Formatter\MultiDigitInstallmentFormatter;
use Paranoia\Core\TransactionType;
use Paranoia\Posnet\Builder\CancelRequestBuilder;
use Paranoia\Posnet\Builder\PostAuthorizationRequestBuilder;
use Paranoia\Posnet\Builder\PreAuthorizationRequestBuilder;
use Paranoia\Posnet\Builder\RefundRequestBuilder;
use Paranoia\Posnet\Builder\SaleRequestBuilder;
use Paranoia\Posnet\Formatter\CustomCurrencyCodeFormatter;
use Paranoia\Posnet\Formatter\ExpireDateFormatter;
use Paranoia\Posnet\Formatter\OrderIdFormatter;

class PosnetBuilderFactory extends AbstractBuilderFactory
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
                    new CustomCurrencyCodeFormatter(),
                    new MoneyFormatter(),
                    new MultiDigitInstallmentFormatter(),
                    new ExpireDateFormatter(),
                    new OrderIdFormatter()
                );
            case TransactionType::CANCEL:
                return new CancelRequestBuilder(
                    $this->configuration,
                    new CustomCurrencyCodeFormatter(),
                    new MoneyFormatter(),
                    new MultiDigitInstallmentFormatter(),
                    new ExpireDateFormatter(),
                    new OrderIdFormatter()
                );
            case TransactionType::REFUND:
                return new RefundRequestBuilder(
                    $this->configuration,
                    new CustomCurrencyCodeFormatter(),
                    new MoneyFormatter(),
                    new MultiDigitInstallmentFormatter(),
                    new ExpireDateFormatter(),
                    new OrderIdFormatter()
                );
            case TransactionType::PRE_AUTHORIZATION:
                return new PreAuthorizationRequestBuilder(
                    $this->configuration,
                    new CustomCurrencyCodeFormatter(),
                    new MoneyFormatter(),
                    new MultiDigitInstallmentFormatter(),
                    new ExpireDateFormatter(),
                    new OrderIdFormatter()
                );
            case TransactionType::POST_AUTHORIZATION:
                return new PostAuthorizationRequestBuilder(
                    $this->configuration,
                    new CustomCurrencyCodeFormatter(),
                    new MoneyFormatter(),
                    new MultiDigitInstallmentFormatter(),
                    new ExpireDateFormatter(),
                    new OrderIdFormatter()
                );
            default:
                throw new NotImplementedError('Not implemented transaction type: ' . $transactionType);
        }
    }
}
