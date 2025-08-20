<?php

namespace App\Enums;

enum Relevance: string {
    case banal = "Banal";
    case relevant = "Relevante";
    case indispensable = "Indispensavel";

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

    // public function description(): string {
    //     return match($this) {
    //         self::banal => 'Transações desnecessárias',
    //         self::relevant => 'Transações originalmente desnecessárias mas atualmente de quitação imprescindível',
    //         self::indispensable => 'Transações necessárias',
    //     };
    // }
}