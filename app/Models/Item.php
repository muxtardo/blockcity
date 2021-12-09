<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price'
    ];

}
