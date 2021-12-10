<?php

namespace App\Models;

use Carbon\Carbon;
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

    // Gera nome de usuário baseado na wallet
    public function getUsername()
	{
		$username = substr($this->wallet, 0, 7);
		$username .= str_repeat('*', 5);
		$username .= substr($this->wallet, -7, 7);

		return $username;
	}

	// Procura pela wallet
    public static function getByWallet($wallet)
    {
        return self::where('wallet', $wallet)
            ->first();
    }

	// Soma dos leveis dos edificios
	public function workers()
	{
		return UserBuilding::where('user_id', $this->id)
			->sum('level');
	}

	// Tras os edificios
	public function buildings()
	{
		return $this->hasMany('App\Models\UserBuilding', 'user_id', 'id');
	}

	// Debita dinheiro
	public function spend($amount)
	{
		return $this->update([
			'currency'	=> $this->currency - $amount
		]);
	}

	// Credita dinheiro
	public function earn($amount)
	{
		return $this->update([
			'currency'	=> $this->currency + $amount
		]);
	}

	// Cria o item
	public function addItem($itemId, $amount = 1)
	{
		$item = UserItem::where('user_id', $this->id)
			->where('item_id', $itemId)->first();
		if ($item) {
			$item->quantity += $amount;
		} else {
			$item	= new UserItem;
			$item->user_id	= $this->id;
			$item->item_id	= $itemId;
			$item->quantity	= $amount;
		}

		return $item->save();
	}

	// Usa o item
	public function useItem($itemId, $amount = 1)
	{
		$item = UserItem::where('user_id', $this->id)
			->where('item_id', $itemId)->first();
		return $item->use($amount);
	}

	// Tem o item?
	public function hasItem($itemId)
	{
		return UserItem::where('user_id', $this->id)
			->where('item_id', $itemId)->first();
	}

	// Verifica se tem banimento ativo
	public function hasBanishment()
	{
		return Banishment::where('user_id', $this->id)
			->where(function ($query) {
            	$query->where('finishes_at', 'is', null)
					->orWhere('finishes_at', '>', Carbon::now());
        })->first();
	}

	// Tem que fazer verificação de captcha?
	public function checkCaptcha()
	{
		return	$this->last_captcha_at &&
				Carbon::parse($this->last_captcha_at)->addMinutes(15) > Carbon::now();
	}

	// Taxa para saque
	public function withdrawFee()
	{
		return 36;
	}

	// Traz o ganho maximo que o cara pode ter no dia
	public function maxDailyClaim()
	{
		$total = 0;
		foreach ($this->buildings as $building) {
			$total += $building->getIncomes();
		}

		return $total;
	}

	public function notifications()
	{
		$notifications	= [];

		return sizeof($notifications) ? $notifications : false;
	}

	public function getPendingTransaction()
	{
		$transaction = Transaction::where('user_id', $this->id)
			->where('status', 'pending')
			->first();

		return $transaction ? $transaction->id : false;
	}
}
