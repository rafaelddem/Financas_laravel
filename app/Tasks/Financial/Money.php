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

    public function extractValue($originalValue)
    {
        if (!isset($originalValue)) 
            return 0.00;

        $firstPosition = substr($originalValue, 0, 1);

        $minus = ($firstPosition == '-') ? '-' : '';

        return $minus . (preg_replace('/[^0-9]/', '', $originalValue) / 100);
    }
}