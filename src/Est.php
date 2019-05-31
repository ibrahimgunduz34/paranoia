<?php
namespace Paranoia;

class Est
{
    /** @var Configuration */
    private $configuration;

    /**
     * Est constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param PurchaseRequest $request
     * @return PurchaseResponse
     */
    public function purchase(PurchaseRequest $request)
    {
    }
}
