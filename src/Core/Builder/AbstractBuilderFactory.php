<?php
namespace Paranoia\Core\Builder;

use Paranoia\Core\Configuration\AbstractConfiguration;

abstract class AbstractBuilderFactory
{
    /** @var AbstractConfiguration */
    protected $configuration;

    /**
     * AbstractBuilderFactory constructor.
     * @param \Paranoia\Core\Configuration\AbstractConfiguration $configuration
     */
    public function __construct(AbstractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $transactionType
     * @return AbstractRequestBuilder
     */
    abstract protected function createBuilder($transactionType);
}
