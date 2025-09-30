<?php

namespace App\Services\ExtractImport;

use App\Models\ExtractModule;
use Carbon\Carbon;

class NuBankCSVExtrator extends ExtratorCSVService
{
    protected function defineModelData(ExtractModule $extractModule, array $attributes): array
    {
        $transactionsAttributes = [
            'title' => mb_strlen($attributes[3]) <= 50 ? $attributes[3] : mb_substr($attributes[3], 0, 47) . '...',
            'transaction_date' => Carbon::createFromFormat('d/m/Y', $attributes[0])->startOfDay(),
            'processing_date' => Carbon::createFromFormat('d/m/Y', $attributes[0])->startOfDay(),
            'description' => $attributes[3],
        ];

        if ($attributes[1] > 0) {
            $transactionsAttributes += [
                'category_id' => $extractModule->transactionBaseIn->category_id,
                'relevance' => $extractModule->transactionBaseIn->category->relevance->value,
                'payment_method_id' => $extractModule->transactionBaseIn->payment_method_id,
                'source_wallet_id' => $extractModule->transactionBaseIn->source_wallet_id,
                'destination_wallet_id' => $extractModule->transactionBaseIn->destination_wallet_id,
                'gross_value' => $attributes[1],
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ];
        } else {
            $transactionsAttributes += [
                'category_id' => $extractModule->transactionBaseOut->category_id,
                'relevance' => $extractModule->transactionBaseOut->category->relevance->value,
                'payment_method_id' => $extractModule->transactionBaseOut->payment_method_id,
                'source_wallet_id' => $extractModule->transactionBaseOut->source_wallet_id,
                'destination_wallet_id' => $extractModule->transactionBaseOut->destination_wallet_id,
                'gross_value' => $attributes[1] * -1,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ];
        }

        return $transactionsAttributes;
    }
}
