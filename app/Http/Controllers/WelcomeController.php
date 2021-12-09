<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
	public function index(Request $request)
	{
		$this->params['totalUser']	= User::count();

		$this->params['page_title']	= __('Home');
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('welcome.index');
	}
}
