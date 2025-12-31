<?php

namespace App\Services\ExtractModule;

use App\Enums\ExtentionFile;
use App\Models\TransactionBase;
use Carbon\Carbon;

class NuBankCSVCredit extends BaseModule
{
    private const INSTALLMENT_IDENTIFIER = " - Parcela ";
    private const INSTALLMENT_TO_IGNORE = [
        "Pagamento recebido",
    ];

    public function __construct()
    {
        parent::__construct("NuBank - Crédito", ExtentionFile::CSV);
    }

    public function defineModelData(string $fileName, array $attributes, TransactionBase $transactionBaseIdIn, TransactionBase $transactionBaseIdOut): array
    {
        if (in_array($attributes[1], self::INSTALLMENT_TO_IGNORE)) 
            return [];

        $installmentDate = Carbon::createFromFormat('Y-m-d', $attributes[0])->startOfDay();
        $cleanTitle = $this->cleanTitle($attributes[1]);

        $transactionsAttributes = [
            'file_name' => $fileName,
            'title' => $cleanTitle,
            'transaction_date' => $installmentDate,
            'processing_date' => $installmentDate,
            'description' => "Título original: " . $attributes[1],
        ];

        if ($attributes[2] > 0) {
            $transactionsAttributes += [
                'category_id' => $transactionBaseIdIn->category_id,
                'relevance' => $transactionBaseIdIn->category->relevance->value,
                'payment_method_id' => $transactionBaseIdIn->payment_method_id,
                'source_wallet_id' => $transactionBaseIdIn->source_wallet_id,
                'destination_wallet_id' => $transactionBaseIdIn->destination_wallet_id,
                'card_id' => $transactionBaseIdIn->card_id,
                'gross_value' => (float) $attributes[2],
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
                'card_id' => $transactionBaseIdOut->card_id,
                'gross_value' => (float) $attributes[2] * -1,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ];
        }

        [$installment_number, $installment_total] = $this->extractInstallmentData($attributes[1]);

        $installmentGrossValue = $attributes[2];
        if ($installment_total > 1) {
            $transactionsAttributes['gross_value'] = $installmentGrossValue * $installment_total;

            $transactionsAttributes += [
                'installment_number' => $installment_number,
                'installment_total' => $installment_total,
                'installment_date' => $installmentDate->format('Y-m-d'),
                'installment_gross_value' => (float) $installmentGrossValue,
                'installment_discount_value' => 0.00,
                'installment_interest_value' => 0.00,
                'installment_rounding_value' => 0.00,
            ];
        } else {
            $transactionsAttributes += [
                'installment_number' => $installment_number,
                'installment_total' => $installment_total,
                'installment_date' => $installmentDate->format('Y-m-d'),
                'installment_gross_value' => (float) $installmentGrossValue,
                'installment_discount_value' => 0.00,
                'installment_interest_value' => 0.00,
                'installment_rounding_value' => 0.00,
            ];
        }

        return $transactionsAttributes;
    }

    private function extractInstallmentData(string $title)
    {
        if (!str_contains($title, self::INSTALLMENT_IDENTIFIER)) 
            return [1, 1];

        $installmentData = explode(self::INSTALLMENT_IDENTIFIER, $title)[1];
        return explode('/', trim($installmentData));
    }

    private function cleanTitle(string $title)
    {
        if (!str_contains($title, self::INSTALLMENT_IDENTIFIER)) 
            return $title;

        $cleanTitle = explode(self::INSTALLMENT_IDENTIFIER, $title)[0];

        return mb_strlen($cleanTitle) <= 50 ? $cleanTitle : mb_substr($cleanTitle, 0, 47) . '...';
    }
}
