<?php

namespace App\Enums;

enum PaymentType: string {
    case Notes = "notes";
    case Transfer = "transfer";
    case Debit = "debit";
    case Credit = "credit";

    public static function values(): array {
        $values = [];
        foreach (self::cases() as $paymentType) {
            $values[$paymentType->name] = $paymentType->value;
        }
        return $values;
    }

    public static function translate(string $key): ?string {
        foreach (self::cases() as $paymentType) {
            if ($paymentType->value === $key) {
                return __($paymentType->name);
            }
        }
        return null;
    }
}
