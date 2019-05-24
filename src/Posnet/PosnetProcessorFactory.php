<?php
namespace Paranoia\Posnet;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\Processor\AbstractProcessorFactory;
use Paranoia\Core\Processor\AbstractResponseProcessor;
use Paranoia\Core\TransactionType;
use Paranoia\Posnet\Processor\CancelResponseProcessor;
use Paranoia\Posnet\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Posnet\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Posnet\Processor\RefundResponseProcessor;
use Paranoia\Posnet\Processor\SaleResponseProcessor;

class PosnetProcessorFactory extends AbstractProcessorFactory
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
