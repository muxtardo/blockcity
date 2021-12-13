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
	public function auth(Request $request)
	{
		// Validate the form data
		$validator = Validator::make($request->all(), [
			'wallet' => 'required|string|size:42',
			'secret' => 'required|string|size:132',
		]);

		// If validation fails
		if ($validator->fails()) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Invalid credentials'),
			], 401);
		}

		// Creedentials received
		$credentials = $validator->validated();

		// Search for the user by wallet
		$user = User::getByWallet(strtolower($credentials['wallet']));
		if ($user && $user->secret != $credentials['secret']) {	// If the user exists and the secret is correct
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Invalid credentials 2'),
			], 401);
		}

		// If the user doesn't exist, create it!
		if (!$user) {
			$user = User::create([
				'wallet' => strtolower($credentials['wallet']),
				'secret' => $credentials['secret'],
			]);
		}

		// Authenticate
		Auth::login($user);

		// sleep(1);

		// Return success
		return $this->json([
			'success'	=> true,
			'redirect'	=> url('dashboard'),
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
		// Logout
		Auth::logout();

		// Invalidate the session
		$request->session()->invalidate();

		// Regenerate the session
		$request->session()->regenerateToken();

		// Redirect to the homepage
		return redirect('/');
	}
}
