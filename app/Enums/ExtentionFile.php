<?php

namespace App\Enums;

enum ExtentionFile: string {
    case CSV = 'csv';
    // case PDF = 'pdf';
    // case XML = 'xml';
    // case JSON = 'json';

    public static function values(): array {
        $values = [];
        foreach (self::cases() as $extention) {
            $values[$extention->name] = $extention->value;
        }
        return $values;
    }
}
