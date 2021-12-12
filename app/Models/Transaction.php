<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'amount',
        'attempts',
        'txid',
        'fee',
    ];



	public function statusColor()
	{
		if ($this->status == 'success') {
			return 'success';
		} elseif ($this->status == 'failed') {
			return 'danger';
		}

		return 'warning';
	}

	private function checkAttempts()
	{
		if ($this->attempts >= 6) {
			$this->status = 'failed';
			$this->save();

			return false;
		}

		return true;
	}

	public function check()
	{
		// Se tiver mais de 6 tentativas, não atualiza para falha
		if (!$this->checkAttempts()) { return false; }

		// Verifica apenas transações pendentes
		if ($this->status != 'pending') { return false; }

		// Atualiza as tentativas
		$this->update([ 'attempts'	=> $this->attempts + 1, ]);

		// Consulta a transação na API
		$apiData	= Http::get(config('game.api_web3_url') . '/transaction/' . $this->txid)->json();
		if (!$apiData) { return false; }
		if (!$apiData['success']) { return false; }

		if ($this->type == 'exchange')
		{
			return $this->processDeposit(Auth::user(), $apiData['receipt']);
		} elseif ($this->type == 'withdrawal')
		{
			return $this->processWithdrawal(Auth::user(), $apiData['receipt']);
		}

		return false;
	}

	private function processDeposit(User $user, $receipt)
	{
		$senderWallet	= strtolower($receipt['sender']);
		$userWallet		= strtolower($user->wallet);
		if ($senderWallet != $userWallet)
		{
			return [
				'success'	=> false,
				'title' 	=> 'Oops!',
				'message'	=> 'Not your transaction'
			];
		}

		$receiverWallet	= strtolower($receipt['receiver']);
		$contractWallet	= strtolower(config('game.contract'));
		if ($receiverWallet != $contractWallet)
		{
			return [
				'success'	=> false,
				'title' 	=> 'Oops!',
				'message'	=> 'Invalid receiver'
			];
		}

		// Update transaction success
		$this->update([ 'status' => 'success' ]);

		// Add User Coins
		$user->earn($receipt['tokens'] / 10000);

		return [
			'success'		=> true,
			'title' 		=> 'Success!',
			'message'		=> 'Transaction completed',
			'currency'		=> currency($user->currency),
			'idTransaction'	=> $user->getPendingTransaction()
		];
	}

	public function publicData()
	{
		return [
			'id'			=> $this->id,
			'type'			=> __(ucwords($this->type)),
			'amount'		=> currency($this->amount),
			'status'		=> __(ucwords($this->status)),
			'statusColor'	=> $this->statusColor(),
			'txid'			=> $this->txid,
			'fee'			=> $this->fee,
			'created_at'	=> $this->created_at->toDateTimeString(),
			'updated_at'	=> $this->updated_at->toDateTimeString(),
		];
	}
}
