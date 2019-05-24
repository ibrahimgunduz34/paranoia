<?php
namespace Paranoia\Nestpay;

use Paranoia\Core\Builder\AbstractBuilderFactory;
use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Pos\AbstractPos;
use Paranoia\Core\Request\Request;

class NestPay extends AbstractPos
{
    /** @var AbstractBuilderFactory */
    private $builderFactory;

    /** @var NestPayProcessorFactory */
    private $processorFactory;

    public function __construct(AbstractConfiguration $configuration)
    {
        parent::__construct($configuration);
        $this->builderFactory = new NestPayBuilderFactory($this->configuration);
        $this->processorFactory = new NestPayProcessorFactory($this->configuration);
    }


    /**
     * {@inheritdoc}
     * @throws \Paranoia\Core\Exception\NotImplementedError
     *@see \Paranoia\Core\Pos\AbstractPos::buildRequest()
     */
    protected function buildRequest(Request $request, $transactionType)
    {
        $rawRequest = $this->builderFactory->createBuilder($transactionType)->build($request);
        return array( 'DATA' => $rawRequest);
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
