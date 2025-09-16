<?php

namespace App\Enums;

enum InvoiceStatus: string {
    case Open = 'open';
    case Closed = 'closed';
    case Overdue = 'overdue';
    case Paid = 'paid';

    public static function values(): array {
        $values = [];
        foreach (self::cases() as $relevance) {
            $values[$relevance->name] = $relevance->value;
        }
        return $values;
    }

    public static function translate(string $key): ?string {
        foreach (self::cases() as $relevance) {
            if ($relevance->value === $key) {
                return __($relevance->name);
            }
        }
        return null;
    }
}
