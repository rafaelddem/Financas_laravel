<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    protected $casts = [
        'name' => 'string',
        'active' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
