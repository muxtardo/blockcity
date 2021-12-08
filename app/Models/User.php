<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet',
        'secret',
        'earnings',
        'currency',
        'presale',
        'last_captcha_check',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // return the user's wallet address 
    public function getUsername()
	{
		$username = substr($this->wallet, 0, 7);
		$username .= str_repeat('*', 5);
		$username .= substr($this->wallet, -7, 7);

		return $username;
	}

    public static function getByWallet($wallet)
    {
        return self::where('wallet', $wallet)
            ->first();
    }

}
