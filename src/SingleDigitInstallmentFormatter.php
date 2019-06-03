<?php
namespace Paranoia;

class SingleDigitInstallmentFormatter
{
    public function format($input)
    {
        return (!is_numeric($input) || intval($input) <= 1) ? null : $input;
    }
}
