<?php

namespace App\Helper;


class PriceHelper
{
    public static function GetFloat(string $price): float
    {
        return (float) str_replace(',', '.', $price);
    }
}
