<?php
namespace Paranoia;

use Paranoia\Exception\InvalidArgumentException;

class DecimalFormatter
{
    public function format($input)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException('The input value must be numeric.');
        }
        return number_format($input, 2, '.', '');
    }
}
