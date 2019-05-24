<?php
namespace Paranoia\Core\Processor;

use Paranoia\Core\Configuration\AbstractConfiguration;

abstract class AbstractResponseProcessor
{
    /** @var \Paranoia\Core\Configuration\AbstractConfiguration */
    protected $configuration;

    /**
     * AbstractResponseProcessor constructor.
     * @param \Paranoia\Core\Configuration\AbstractConfiguration $configuration
     */
    public function __construct(AbstractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    abstract protected function validateResponse($transformedResponse);

    abstract public function process($rawResponse);
}
