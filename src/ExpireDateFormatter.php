<?php
namespace Paranoia;

class ExpireDateFormatter
{
    public function format($input)
    {
        return sprintf('%02s/%04s', $input[0], $input[1]);
    }
}
