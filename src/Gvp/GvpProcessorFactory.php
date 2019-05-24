<?php
namespace Paranoia\Gvp;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\Processor\AbstractProcessorFactory;
use Paranoia\Core\Processor\AbstractResponseProcessor;
use Paranoia\Core\TransactionType;
use Paranoia\Gvp\Processor\CancelResponseProcessor;
use Paranoia\Gvp\Processor\PostAuthorizationResponseProcessor;
use Paranoia\Gvp\Processor\PreAuthorizationResponseProcessor;
use Paranoia\Gvp\Processor\RefundResponseProcessor;
use Paranoia\Gvp\Processor\SaleResponseProcessor;

class GvpProcessorFactory extends AbstractProcessorFactory
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
