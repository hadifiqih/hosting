<?php

namespace App\Helpers;

class CustomHelper 
{
    public static function removeCurrencyFormat($value)
    {
        $value = str_replace('Rp ', '', $value);
        $value = str_replace('.', '', $value);
        $value = intval($value);
        return $value;
    }

    public static function addCurrencyFormat($value)
    {
        $value = "Rp " . number_format($value, 0, ',', '.');
        return $value;
    }
}
