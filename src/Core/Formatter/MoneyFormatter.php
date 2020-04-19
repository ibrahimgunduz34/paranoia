<?php
namespace Paranoia\Core\Formatter;

use Paranoia\Core\Exception\InvalidArgumentException;

class MoneyFormatter
{
    /**
     * @param float|null $input
     * @return string
     */
    public function format(?float $input): string
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException('The input value must be numeric.');
        }
        return round($input * 100);
    }
}