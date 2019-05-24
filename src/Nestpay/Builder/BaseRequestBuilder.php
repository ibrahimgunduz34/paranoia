<?php
namespace Paranoia\Nestpay\Builder;

use Paranoia\Core\Builder\AbstractRequestBuilder;
use Paranoia\Core\Configuration\AbstractConfiguration;
use Paranoia\Core\Formatter\DecimalFormatter;
use Paranoia\Core\Formatter\IsoNumericCurrencyCodeFormatter;
use Paranoia\Core\Formatter\SingleDigitInstallmentFormatter;
use Paranoia\Core\Request\Resource\Card;
use Paranoia\Core\Request\Resource\ResourceInterface;
use Paranoia\Nestpay\Formatter\ExpireDateFormatter;

abstract class BaseRequestBuilder extends AbstractRequestBuilder
{
    /** @var \Paranoia\Core\Formatter\DecimalFormatter */
    protected $amountFormatter;

    /** @var  IsoNumericCurrencyCodeFormatter */
    protected $currencyCodeFormatter;

    /** @var  \Paranoia\Core\Formatter\SingleDigitInstallmentFormatter */
    protected $installmentFormatter;

    /** @var  ExpireDateFormatter */
    protected $expireDateFormatter;

    public function __construct(
        AbstractConfiguration $configuration,
        IsoNumericCurrencyCodeFormatter $currencyCodeFormatter,
        DecimalFormatter $amountFormatter,
        SingleDigitInstallmentFormatter $installmentFormatter,
        ExpireDateFormatter $expireDateFormatter
    ) {
        parent::__construct($configuration);
        $this->amountFormatter = $amountFormatter;
        $this->currencyCodeFormatter = $currencyCodeFormatter;
        $this->installmentFormatter = $installmentFormatter;
        $this->expireDateFormatter = $expireDateFormatter;
    }

    protected function buildBaseRequest($type)
    {
        /** @var \Paranoia\Nestpay\Configuration\NestPay $config */
        $config = $this->configuration;
        return [
            'Mode'     => $config->getMode(),
            'ClientId' => $config->getClientId(),
            'Name'     => $config->getUsername(),
            'Password' => $config->getPassword(),
            'Type'     =>  $type,
        ];
    }

    protected function buildCard(ResourceInterface $card)
    {
        assert($card instanceof Card);

        /** @var Card $_card */
        $_card = $card;

        $expireDate = $this->expireDateFormatter->format(
            [
                $_card->getExpireMonth(),
                $_card->getExpireYear()
            ]
        );

        return array(
            'Number'     => $_card->getNumber(),
            'Cvv2Val'    => $_card->getSecurityCode(),
            'Expires'    => $expireDate
        );
    }
}
