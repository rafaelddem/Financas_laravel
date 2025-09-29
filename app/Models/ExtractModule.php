<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtractModule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'transaction_base_in_id',
        'transaction_base_out_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionBaseIn(): BelongsTo
    {
        return $this->belongsTo(TransactionBase::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionBaseOut(): BelongsTo
    {
        return $this->belongsTo(TransactionBase::class);
    }
}
