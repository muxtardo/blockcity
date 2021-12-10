<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function checkById(Request $request)
	{
		$transaction = Transaction::find($request->id);

		if (!$transaction) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Transaction not found')
			]);
		}

		return $this->json([
			'success'	=> true,
			'title'		=> __('Success'),
			'message'	=> __('Transaction has ben processed!')
		]);
	}
}
