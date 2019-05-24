<?php
namespace Paranoia\Posnet;

use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Pos\AbstractPos;
use Paranoia\Core\Request\Request;

class Posnet extends AbstractPos
{
    /** @var PosnetBuilderFactory */
    private $builderFactory;

    /** @var PosnetProcessorFactory */
    private $processorFactory;

    public function __construct(AbstractConfiguration $configuration)
    {
        parent::__construct($configuration);
        $this->builderFactory = new PosnetBuilderFactory($this->configuration);
        $this->processorFactory = new PosnetProcessorFactory($this->configuration);
    }

    /**
     * {@inheritdoc}
     * @throws \Paranoia\Core\Exception\NotImplementedError
     *@see \Paranoia\Core\Pos\AbstractPos::buildRequest()
     */
    protected function buildRequest(Request $request, $transactionType)
    {
        $rawRequest = $this->builderFactory->createBuilder($transactionType)->build($request);
        return array( 'xmldata' => $rawRequest);
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
