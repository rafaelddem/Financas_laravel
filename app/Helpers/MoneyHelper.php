<?php

namespace App\Helpers;

class MoneyHelper
{
    public static function format($value, $prefix = 'R$')
    {
        return $prefix . ' ' . number_format($value, 2, ',', '.');
    }
}