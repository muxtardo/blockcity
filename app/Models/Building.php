<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory, SoftDeletes;

	public function mint(User $user)
	{
		// Fazer a criação da nova casa
	}

	// Retornar todas as casas relacionadas
	public function built()
	{
		$this->hasMany('App\Models\UserBuilding', 'building_id', 'id');
	}
}
