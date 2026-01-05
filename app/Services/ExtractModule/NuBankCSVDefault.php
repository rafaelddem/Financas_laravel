<?php

namespace App\Services\ExtractModule;

use App\Enums\ExtentionFile;
use App\Models\TransactionBase;
use Carbon\Carbon;

class NuBankCSVDefault extends BaseModule
{
    public function __construct()
    {
        parent::__construct("NuBank - Débito", ExtentionFile::CSV);
    }

    public function defineModelData(string $fileName, array $attributes, TransactionBase $transactionBaseIdIn, TransactionBase $transactionBaseIdOut): array
    {
        $originalDescription = str_replace("•", "*", $attributes[3]);

        $transactionsAttributes = [
            'file_name' => $fileName,
            'title' => mb_strlen($originalDescription) <= 50 ? $originalDescription : mb_substr($originalDescription, 0, 47) . '...',
            'transaction_date' => Carbon::createFromFormat('d/m/Y', $attributes[0])->startOfDay(),
            'processing_date' => Carbon::createFromFormat('d/m/Y', $attributes[0])->startOfDay(),
            'description' => $originalDescription,
        ];

        if ($attributes[1] > 0) {
            $transactionsAttributes += [
                'category_id' => $transactionBaseIdIn->category_id,
                'relevance' => $transactionBaseIdIn->category->relevance->value,
                'payment_method_id' => $transactionBaseIdIn->payment_method_id,
                'source_wallet_id' => $transactionBaseIdIn->source_wallet_id,
                'destination_wallet_id' => $transactionBaseIdIn->destination_wallet_id,
                'gross_value' => $attributes[1],
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ];
        } else {
            $transactionsAttributes += [
                'category_id' => $transactionBaseIdOut->category_id,
                'relevance' => $transactionBaseIdOut->category->relevance->value,
                'payment_method_id' => $transactionBaseIdOut->payment_method_id,
                'source_wallet_id' => $transactionBaseIdOut->source_wallet_id,
                'destination_wallet_id' => $transactionBaseIdOut->destination_wallet_id,
                'gross_value' => $attributes[1] * -1,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ];
        }

        return $transactionsAttributes;
    }
}
