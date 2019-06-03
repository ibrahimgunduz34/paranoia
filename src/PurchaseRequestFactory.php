<?php
namespace Paranoia;

class PurchaseRequestFactory
{
    /** @var PurchaseRequestBuilder */
    private $requestBuilder;

    /**
     * PurchaseRequestFactory constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->requestBuilder = new PurchaseRequestBuilder(
            $configuration,
            new DecimalFormatter(),
            new IsoNumericCurrencyCodeFormatter(),
            new SingleDigitInstallmentFormatter(),
            new ExpireDateFormatter()
        );
    }

    public function create(PurchaseRequest $request)
    {
        $this->requestBuilder->withAmount($request->getAmount())
            ->withCurrency($request->getCurrency())
            ->withInstallment($request->getInstallment())
            ->withOrderId($request->getOrderId())
            ->withCardNumber($request->getCardNumber())
            ->withExpires($request->getCardExpireMonth(), $request->getCardExpireYear())
            ->withCvv2Val($request->getCardSecurityCode());
        return $this->requestBuilder->__toString();
    }
}
