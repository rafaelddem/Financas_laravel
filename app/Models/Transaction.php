<?php

namespace App\Models;

use App\Enums\Relevance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title', 
        'transaction_date', 
        'processing_date', 
        'transaction_type_id', 
        'relevance', 
        'payment_method_id', 
        'source_wallet_id', 
        'destination_wallet_id', 
        'card_id', 
        'description', 
        'gross_value', 
        'discount_value', 
        'interest_value', 
        'rounding_value',
    ];

    protected $casts = [
        'transaction_date' => 'date', 
        'processing_date' => 'date', 
        'relevance' => Relevance::class, 
        'gross_value' => 'float', 
        'discount_value' => 'float', 
        'interest_value' => 'float', 
        'rounding_value' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sourceWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'source_wallet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destinationWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'destination_wallet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function netValue(bool $formated = false): float|string
    {
        $net_value = $this->gross_value - $this->discount_value + $this->interest_value + $this->rounding_value;
        return $formated
            ? 'R$ ' . number_format($net_value, 2, ',', '.')
            : $net_value;
    }
}
