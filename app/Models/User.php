<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	public function getUsername()
	{
		$username = substr($this->wallet, 0, 7);
		$username .= '*****';
		$username .= substr($this->wallet, -7, 7);

		return $username;
	}
}
