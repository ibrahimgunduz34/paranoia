<?php
namespace Paranoia;

class PurchaseRequest
{
    /** @var string */
    private $orderId;

    /** @var float */
    private $amount;

    //TODO: It's gonna be Currency enum that provided by a money library/implementation.
    private $currency;

    /** @var integer */
    private $installment;

    /** @var string */
    private $cardNumber;

    /** @var integer */
    private $cardExpireMonth;

    /** @var integer */
    private $cardExpireYear;

    /** @var integer */
    private $cardSecurityCode;
}
