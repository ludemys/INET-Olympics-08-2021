<?php

namespace App\Helpers;

/**
 * @param string $number
 * @param int $upTo
 *
 * @return string
 */
function addZerosToLeft(string $number, int $upTo)
{
    $zeros = '';

    if (strlen($number) < $upTo)
    {
        for ($i = 0; $i < $upTo - $number; $i++)
        {
            $zeros .= '0';
        }
    }
    return $zeros . $number;
}
