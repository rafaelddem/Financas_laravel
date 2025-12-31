<?php

namespace App\Services\ExtractModule;

use App\Enums\ExtentionFile;
use App\Models\TransactionBase;

abstract class BaseModule
{
    private string $font;
    private ExtentionFile $format;
    private bool $hasHeaderOnFile;

    public function __construct(string $font, ExtentionFile $format, bool $hasHeaderOnFile = true)
    {
        $this->font = $font;
        $this->format = $format;
        $this->hasHeaderOnFile = $hasHeaderOnFile;
    }

    public function hasHeaderOnFile()
    {
        return $this->hasHeaderOnFile;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function toString()
    {
        return $this->font . " (" . $this->format->name . ")";
    }

    abstract public function defineModelData(string $fileName, array $attributes, TransactionBase $transactionBaseIdIn, TransactionBase $transactionBaseIdOut): array;
}
