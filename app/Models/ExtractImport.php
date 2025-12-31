<?php

namespace App\Models;

use App\Enums\PaymentType;
use App\Enums\Relevance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtractImport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'file_name', 
        'title', 
        'ready', 
        'transaction_date', 
        'processing_date', 
        'category_id', 
        'relevance', 
        'payment_method_id', 
        'source_wallet_id', 
        'destination_wallet_id', 
        'card_id', 
        'gross_value', 
        'discount_value', 
        'interest_value', 
        'rounding_value',
        'description', 
        'installment_number',
        'installment_total',
        'installment_date',
        'installment_gross_value',
        'installment_discount_value',
        'installment_interest_value',
        'installment_rounding_value',
    ];

    protected $casts = [
        'ready' => 'boolean', 
        'transaction_date' => 'date', 
        'processing_date' => 'date', 
        'relevance' => Relevance::class, 
        'gross_value' => 'float', 
        'discount_value' => 'float', 
        'interest_value' => 'float', 
        'rounding_value' => 'float',
        'installment_date' => 'date',
        'installment_gross_value' => 'float',
        'installment_discount_value' => 'float',
        'installment_interest_value' => 'float',
        'installment_rounding_value' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function convertToTransactionData()
    {
        $data = [
            'title' => $this->title, 
            'transaction_date' => $this->transaction_date, 
            'processing_date' => $this->processing_date, 
            'category_id' => $this->category_id, 
            'relevance' => $this->relevance, 
            'payment_method_id' => $this->payment_method_id, 
            'source_wallet_id' => $this->source_wallet_id, 
            'destination_wallet_id' => $this->destination_wallet_id, 
            'card_id' => $this->card_id, 
            'gross_value' => $this->gross_value, 
            'discount_value' => $this->discount_value, 
            'interest_value' => $this->interest_value, 
            'rounding_value' => $this->rounding_value,
            'description' => $this->description, 
        ];

        if ($this->paymentMethod->type == PaymentType::Credit) {
            $data['installments'] = [];
            for ($item = 0; $item < $this->installment_total; $item++) { 
                $data['installments'][$item] = [
                    'gross_value' => $this->installment_gross_value, 
                    'discount_value' => $this->installment_discount_value, 
                    'interest_value' => $this->installment_interest_value, 
                    'rounding_value' => $this->installment_rounding_value,
                    'installment_date' => $this->installment_date->clone()->addMonths($item)->format('Y-m-d'), 
                ];
            }
        }

        return $data;
    }
}
