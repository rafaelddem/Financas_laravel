<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'link', 
        'important', 
        'read', 
    ];

    protected $casts = [
        'important' => 'boolean', 
        'read' => 'boolean', 
    ];
}
