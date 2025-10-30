<?php

namespace App\Models;

use App\Enums\Relevance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function convertToTransaction()
    {
        return new Transaction([
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
        ]);
    }
}
