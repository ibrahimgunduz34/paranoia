<?php
namespace Paranoia\Posnet\Builder;

use Paranoia\Core\Builder\AbstractRequestBuilder;
use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Formatter\MoneyFormatter;
use Paranoia\Core\Formatter\MultiDigitInstallmentFormatter;
use Paranoia\Core\Request\Request;
use Paranoia\Core\Request\Resource\Card;
use Paranoia\Core\Request\Resource\ResourceInterface;
use Paranoia\Posnet\Configuration\Posnet;
use Paranoia\Posnet\Formatter\CustomCurrencyCodeFormatter;
use Paranoia\Posnet\Formatter\ExpireDateFormatter;
use Paranoia\Posnet\Formatter\OrderIdFormatter;

abstract class BaseRequestBuilder extends AbstractRequestBuilder
{
    /** @var \Paranoia\Core\Formatter\MoneyFormatter */
    protected $amountFormatter;

    /** @var  CustomCurrencyCodeFormatter */
    protected $currencyCodeFormatter;

    /** @var  MultiDigitInstallmentFormatter */
    protected $installmentFormatter;

    /** @var  ExpireDateFormatter */
    protected $expireDateFormatter;

    /** @var OrderIdFormatter OrderId */
    protected $orderIdFormatter;

    public function __construct(
        AbstractConfiguration $configuration,
        CustomCurrencyCodeFormatter $currencyCodeFormatter,
        MoneyFormatter $amountFormatter,
        MultiDigitInstallmentFormatter $installmentFormatter,
        ExpireDateFormatter $expireDateFormatter,
        OrderIdFormatter $orderIdFormatter
    ) {
        parent::__construct($configuration);
        $this->currencyCodeFormatter = $currencyCodeFormatter;
        $this->amountFormatter = $amountFormatter;
        $this->installmentFormatter = $installmentFormatter;
        $this->expireDateFormatter = $expireDateFormatter;
        $this->orderIdFormatter = $orderIdFormatter;
    }

    protected function buildBaseRequest(Request $request)
    {
        /** @var Posnet $configuration */
        $configuration = $this->configuration;
        return [
            'mid' => $configuration->getMerchantId(),
            'tid' => $configuration->getTerminalId(),
            'username' => $configuration->getUsername(),
            'password' => $configuration->getPassword()
        ];
    }

    protected function buildCard(ResourceInterface $card)
    {
        assert($card instanceof Card);

        /** @var Card $_card */
        $_card = $card;

        return [
            'ccno' => $_card->getNumber(),
            'cvc' => $_card->getSecurityCode(),
            'expDate' => $this->expireDateFormatter->format(
                [
                    $_card->getExpireMonth(),
                    $_card->getExpireYear()
                ]
            )
        ];
    }
}
