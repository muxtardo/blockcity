<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'item_id',
        'quantity',
    ];

	public function base()
	{
		return $this->belongsTo('App\Models\Item', 'item_id', 'id');
	}

	// Usa o item, faz o decremento e caso não tenha mais unidades, apaga
	public function use($amount = 1)
	{
		$this->quantity -= $amount;
		if ($this->quantity <= 0) {
			return $this->delete();
		} else {
			return $this->save();
		}
	}
}
