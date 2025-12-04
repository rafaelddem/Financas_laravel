<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 
        'installment_number', 
        'installment_total', 
        'installment_date', 
        'gross_value', 
        'discount_value', 
        'interest_value', 
        'rounding_value',
    ];

    protected $casts = [
        'installment_date' => 'date', 
        'gross_value' => 'float', 
        'discount_value' => 'float', 
        'interest_value' => 'float', 
        'rounding_value' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getNetValueAttribute(): float
    {
        return $this->gross_value - $this->discount_value + $this->interest_value + $this->rounding_value;
    }

    public function getNetValueFormattedAttribute(): string
    {
        return MoneyHelper::format($this->net_value);
    }
}
