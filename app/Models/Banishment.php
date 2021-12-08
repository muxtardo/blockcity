<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banishment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'user_id',
        'reason',
        'finishes_at',
    ];

    protected $casts = [
        'finishes_at' => 'datetime',
    ];
}
