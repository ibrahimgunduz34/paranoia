<?php
namespace Paranoia;

use DOMNode;

abstract class BaseRequestBuilder extends AbstractXmlRequestBuilder
{
    const ROOT_NODE = 'CC5Request';

    /** @var DOMNode */
    private $root;

    /** @var DecimalFormatter */
    protected $amountFormatter;

    /** @var IsoNumericCurrencyCodeFormatter  */
    protected $currencyFormatter;

    /** @var SingleDigitInstallmentFormatter */
    protected $installmentFormatter;

    /** @var ExpireDateFormatter */
    protected $expireDateFormatter;

    /**
     * BaseRequestBuilder constructor.
     * @param $requestType
     * @param Configuration $configuration
     * @param DecimalFormatter $amountFormatter
     * @param IsoNumericCurrencyCodeFormatter $currencyFormatter
     * @param SingleDigitInstallmentFormatter $installmentFormatter
     */
    public function __construct(
        $requestType,
        Configuration $configuration,
        DecimalFormatter $amountFormatter,
        IsoNumericCurrencyCodeFormatter $currencyFormatter,
        SingleDigitInstallmentFormatter $installmentFormatter,
        ExpireDateFormatter $expireDateFormatter
    ) {
        $this->amountFormatter = $amountFormatter;
        $this->currencyFormatter = $currencyFormatter;
        $this->installmentFormatter = $installmentFormatter;
        $this->expireDateFormatter = $expireDateFormatter;

        parent::__construct(self::ROOT_NODE);
        $this->root = $this->getObject()->firstChild;
        //TODO: Move configuration keys to constants within a suitable place.
        $this->prepareBaseNodes(
            $requestType,
            $configuration->get('NAME'),
            $configuration->get('PASSWORD'),
            $configuration->get("CLIENT_ID")
        );
    }

    /**
     * @param string $requestType
     * @param string $name
     * @param string $password
     * @param string $clientId
     */
    private function prepareBaseNodes($requestType, $name, $password, $clientId)
    {
        $this->createElement($this->root, "Name", $name);
        $this->createElement($this->root, "Password", $password);
        $this->createElement($this->root, "ClientId", $clientId);
        $this->createElement($this->root, "Type", $requestType);
    }

    protected function getRoot()
    {
        return $this->root;
    }
}