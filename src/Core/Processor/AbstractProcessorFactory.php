<?php
namespace Paranoia\Core\Processor;

use Paranoia\Core\Configuration\AbstractConfiguration;

abstract class AbstractProcessorFactory
{
    /** @var AbstractConfiguration */
    protected $configuration;

    /**
     * AbstractProcessorFactory constructor.
     * @param AbstractConfiguration $configuration
     */
    public function __construct(AbstractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $transactionType
     * @return AbstractResponseProcessor
     */
    abstract public function createProcessor($transactionType);
}
