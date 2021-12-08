<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	/**
	 * Handle an authentication attempt.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request)
	{
		$user = User::first();
		if (empty($user)) {
			abort(404);
		}

		if (Auth::loginUsingId($user->id)) {
			return redirect('/?' . Auth::user()->wallet);
		}

		// if (Auth::attempt(
		// 	[
		// 		'wallet'	=> 'aaaaaaaaaaaaa',
		// 		'password'	=> 'aaaaaaaaaaaaa',
		// 	]
		// )) {
		// 	$request->session()->regenerate();

		// 	return redirect()->intended();
		// }

		// return redirect('/')->withErrors([
		// 	'wallet' => 'The provided credentials do not match our records.',
		// ]);
	}
}
