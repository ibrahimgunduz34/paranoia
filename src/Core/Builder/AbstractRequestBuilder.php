<?php
namespace Paranoia\Core\Builder;

use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Request\Request;

abstract class AbstractRequestBuilder
{
    /** @var AbstractConfiguration */
    protected $configuration;

    /**
     * AbstractRequestBuilder constructor.
     * @param \Paranoia\Core\Configuration\AbstractConfiguration $configuration
     */
    public function __construct(AbstractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    abstract public function build(Request $request);
}
