<?php

namespace App\Enums;

enum PaymentType: string {
    case notes = "Dinheiro físico";
    case transfer = "Transação bancária";
    case debit = "Cartão débito";
    case credit = "Cartão crédito";

    public static function names(): array {
        return array_map(fn($m) => $m->name, self::cases());
    }

    public static function values(): array {
        $values = [];
        foreach (self::cases() as $relevance) {
            $values[$relevance->name] = $relevance->value;
        }
        return $values;
    }

    public static function get(string $key): ?string {
        foreach (self::cases() as $relevance) {
            if ($relevance->name === $key) {
                return $relevance->value;
            }
        }
        return null;
    }
}