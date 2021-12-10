<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExchangeController extends Controller
{
    public function index()
	{
		$this->params['transactions'] = Transaction::where('user_id', Auth::user()->id)
			->orderBy('created_at', 'desc')->paginate(10);

		$this->params['page_title'] = __('Exchange');
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('exchange.index');
	}

	public function check(Request $request)
	{
		// Validate the form data
		$validator = Validator::make($request->all(), [
			'hash' => 'required|string|size:66'
		]);

		// If validation fails
		if ($validator->fails()) {
			return $this->json([
				'success'	=> false,
				'title'		=> _('Error'),
				'message'	=> _('Invalid data')
			], 401);
		}

		// Get post params
		$params = $validator->validated();

		// Check if hash exists
		$transaction = Transaction::where('txid', $params['hash'])->first();
		if (!$transaction) {
			return $this->json([
				'success'	=> false,
				'title'		=> _('Error'),
				'message'	=> _('Transaction not found')
			], 401);
		}

		// Check transaction with API
		if (($check	= $transaction->check())) {
			return $this->json($check, $check['success'] ? 200 : 401);
		} else {
			return $this->json([
				'success'	=> true,
				'title'		=> _('Error'),
				'message'	=> _('Fail to check this transaction, maybe this transaction already processed!')
			], 401);
		}
	}

	public function deposit(Request $request)
	{
		// Validate the form data
		$validator = Validator::make($request->all(), [
			'amount'	=> 'required|numeric|min:0.0001',
			'hash'		=> 'required|string|size:66',
		]);

		// If validation fails
		if ($validator->fails()) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Invalid data')
			], 401);
		}

		// Get post params
		$params = $validator->validated();

		// Check if hash exists
		$checkHash = Transaction::where('txid', $params['hash'])->exists();
		if ($checkHash) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Transaction already exists'),
			], 401);
		}

		// Check if amount is valid
		if (!is_numeric($params['amount']) || $params['amount'] < 0) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Invalid amount'),
			], 401);
		}

		// Create transaction
		$transaction	= addTransaction([
			'txid'		=> $params['hash'],
			'amount'	=> $params['amount'],
			'type'		=> 'exchange',
			'user_id'	=> Auth::user()->id,
			'status'	=> 'pending'
		]);
		if ($transaction) {
			return $this->json([
				'success'		=> true,
				'title'			=> __('Success'),
				'message'		=> __('Transaction registered successfully!'),
				'transactionId'	=> $transaction->id
			]);
		} else {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Something went wrong'),
			], 401);
		}
	}
}
