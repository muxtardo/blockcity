<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBuilding;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
	public function index(Request $request)
	{
		$this->params['totalPlayers']		= User::count();
		$this->params['totalHouses']		= UserBuilding::count();
		$this->params['totalTransactions']	= Transaction::count();
		$this->params['totalWithdrawals']	= 0;

		$this->params['page_title']	= __('Home');
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('welcome.index');
	}
}
