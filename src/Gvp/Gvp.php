<?php
namespace Paranoia\Gvp;

use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Pos\AbstractPos;
use Paranoia\Core\Request\Request;

class Gvp extends AbstractPos
{
    /** @var GvpBuilderFactory */
    private $builderFactory;

    /** @var  \Paranoia\Gvp\GvpProcessorFactory */
    private $processorFactory;

    public function __construct(AbstractConfiguration $configuration)
    {
        parent::__construct($configuration);
        $this->builderFactory = new GvpBuilderFactory($this->configuration);
        $this->processorFactory = new GvpProcessorFactory($this->configuration);
    }

    /**
     * {@inheritdoc}
     * @throws \Paranoia\Core\Exception\NotImplementedError
     *@see \Paranoia\Core\Pos\AbstractPos::buildRequest()
     */
    protected function buildRequest(Request $request, $transactionType)
    {
        $rawRequest = $this->builderFactory->createBuilder($transactionType)->build($request);
        return array( 'data' => $rawRequest);
    }

    /**
     * {@inheritdoc}
     * @see \Paranoia\Core\Pos\AbstractPos::parseResponse()
     */
    protected function parseResponse($rawResponse, $transactionType)
    {
        return $this->processorFactory->createProcessor($transactionType)->process($rawResponse);
    }
}
