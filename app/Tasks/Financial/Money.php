<?php

namespace App\Tasks\Financial;

use Exception;

class Money
{
    private float $value;

    public function __construct($value = 0)
    {
        $this->value = $this->extractValue($value);
    }

    private function extractValue($originalValue) : float
    {
        if (is_string($originalValue)) {
            $cleanCaracteres = preg_replace('/[^0-9]/', '', $originalValue);
            if (in_array($cleanCaracteres, ["", 0])) 
                return floatval(0);
    
            $finalValue = floatval($cleanCaracteres) / 100;
    
            if (substr($originalValue, 0, 1) == '-') 
                $finalValue = $finalValue * -1;
    
            return round(floatval($finalValue), 2);
        } elseif (is_numeric($originalValue)) {
            return round(floatval($originalValue), 2);
        } else {
            throw new Exception("Valor informado não está em um formato passível de conversão");
        }
    }

    public function getValue() : float
    {
        return $this->value;
    }

    public function formatMoney() : string
    {
        $negative = $this->value < 0;

        $response = $this->value;
        if ($negative) 
            $response = $response * -1;

        $formattedValue  = ($negative) ? "-" : "";
        $formattedValue .= 'R$ ' . number_format($response, 2, ',', '.');

        return $formattedValue;
    }
}