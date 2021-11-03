<?php

namespace App\Tasks\Financial;

class Money
{
    public function formatValue(float $value, bool $valueInCents = true)
    {
        $formattedValue = '';

        if ($value < 0) {
            $formattedValue = '-';
            $value = floatval(substr($value, 1, strlen($value)));
        }

        if (!$valueInCents) {
            $value *= 100;
        }

        $formattedValue .= 'R$ ' . number_format($value, 2, ',', '.');

        return $formattedValue;
    }
}