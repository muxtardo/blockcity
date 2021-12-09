<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingStatus extends Model
{
    use HasFactory;

	protected $fillable = [
        'name',
        'color',
        'loss',
        'fix_price',
    ];
}
