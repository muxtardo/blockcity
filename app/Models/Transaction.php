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

	public function check()
	{
		// Se tiver mais de 6 tentativas, não atualiza para falha
		if ($this->attempts >= 6)
		{
			$this->status = 'failed';
			$this->save();

			return false;
		}

		// Verifica apenas transações pendentes
		if ($this->status != 'pending') { return false; }

		// Atualiza as tentativas
		$this->update([ 'attempts'	=> $this->attempts + 1, ]);

		// idle time
		sleep(2);

		// Consulta a transação na API
		$apiData	= getTransactionApi($this->txid);
		if (!$apiData) { return false; }

		// Get user account
		$user = Auth::user();

		// Get receipt from API
		$receipt	= $apiData['receipt'];
		if ($this->type == 'exchange')
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
					'message'	=> 'Invalid receiver ' . $receiverWallet . ' - ' . $contractWallet
				];
			}

			// Update transaction success
			$this->update([ 'status' => 'success' ]);

			// Add User Coins
			$user->earn($receipt['tokens'] / 10000);

			return [
				'success'	=> true,
				'title' 	=> 'Success!',
				'message'	=> 'Transaction completed',
				'attemps'	=> $this->attemps,
				'finished'	=> $this->status == 'success',
				'currency'	=> currency($user->currency)
			];
		} else
		{

		}
	}
}
