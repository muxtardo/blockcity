<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
		$validator = Validator::make($request->all(), [
			'wallet' => 'required|string|size:42', 
			'secret' => 'required|string|size:132',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'title' => _('Error'),
				'message' => _('Invalid credentials'),
			], 401);
		}

		$data = $validator->validated();

		$user = User::getByWallet($data['wallet']);

		if ($user && $user->secret != $data['secret']) {
			return response()->json([
				'success' => false,
				'title' => _('Error'),
				'message' => _('Invalid credentials'),
			], 401);
		} 

		if (!$user) {
			$user = User::create([
				'wallet' => $data['wallet'],
				'secret' => $data['secret'],
			]);
		}

		Auth::login($user);

		return response()->json([
			'success' => true,
			'title' => _('Success'),
			'message' => _('You are now logged in'),
			'redirect' => url('/'),
		]);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
