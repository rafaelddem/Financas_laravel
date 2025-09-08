<?php

namespace App\Enums;

enum Relevance: string {
    case Banal = 'banal';
    case Relevant = 'relevant';
    case Indispensable = 'indispensable';

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

    // public function description(): string {
    //     return match($this) {
    //         self::banal => 'Transações desnecessárias',
    //         self::relevant => 'Transações originalmente desnecessárias mas atualmente de quitação imprescindível',
    //         self::indispensable => 'Transações necessárias',
    //     };
    // }
}
