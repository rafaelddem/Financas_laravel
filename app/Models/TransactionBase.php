<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionBase extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title', 
        'category_id', 
        'payment_method_id', 
        'source_wallet_id', 
        'destination_wallet_id', 
        'card_id', 
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

    public function __toString(): string
    {
        $presentation = vsprintf("%s: %s | Usando %s, transferindo de %s para %s %s", [
            $this->title,
            $this->category->name,
            $this->paymentMethod->name,
            $this->sourceWallet->name,
            $this->destinationWallet->name,
            $this->card ? '(' . $this->card->name . ')' : ''
        ]);

        return $presentation;
    }
}
