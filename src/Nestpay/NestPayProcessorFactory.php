<?php
namespace Paranoia\Nestpay;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\Processor\AbstractProcessorFactory;
use Paranoia\Core\Processor\AbstractResponseProcessor;
use Paranoia\Core\TransactionType;
use Paranoia\Nestpay\Processor\CancelResponseProcessor;
use Paranoia\Nestpay\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Nestpay\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Nestpay\Processor\RefundResponseProcessor;
use Paranoia\Nestpay\Processor\SaleResponseProcessor;

class NestPayProcessorFactory extends AbstractProcessorFactory
{
    /**
     * @param string $transactionType
     * @return AbstractResponseProcessor
     */
    public function createProcessor($transactionType)
    {
        switch ($transactionType) {
            case TransactionType::SALE:
                return new SaleResponseProcessor($this->configuration);
            case TransactionType::REFUND:
                return new RefundResponseProcessor($this->configuration);
            case TransactionType::CANCEL:
                return new CancelResponseProcessor($this->configuration);
            case TransactionType::PRE_AUTHORIZATION:
                return new PreAuthorizationResponseProcessor($this->configuration);
            case TransactionType::POST_AUTHORIZATION:
                return new PostAuthorizationResponseProcessor($this->configuration);
            default:
                throw new InvalidArgumentException('Bad transaction type: ' . $transactionType);
        }
    }
}
