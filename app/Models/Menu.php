<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'url', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
