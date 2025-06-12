<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DiscountCode extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'is_active',
        'user_id',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
