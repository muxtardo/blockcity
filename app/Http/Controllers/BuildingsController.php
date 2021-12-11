<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\UserBuilding;


class BuildingsController extends Controller
{
	public function index(Request $request)
	{
		$userBuildings		= $request->user()->buildings();
		$getUserBuildings	= $userBuildings->orderBy('highlight', 'desc')
			->orderBy('last_claim_at', 'asc')->paginate(6);

		$buildings = [];
		foreach ($getUserBuildings as $building) {
			$buildings[] = $building->publicData();
		}

		return $this->json([
			'success'	=> true,
			'buildings'	=> $buildings,
			'stats'  	=> [
				'currency'		=> currency($request->user()->currency),
				'buildings'		=> $request->user()->buildings()->count(),
				'workers'		=> $request->user()->workers(),
				'dailyClaim'	=> currency($request->user()->maxDailyClaim())
			]
		]);
	}

    public function mint(Request $request)
    {
		// Instancia o usuario
		$user = $request->user();

		// Verifica o custo
		$cost = config('game.mint_cost');
		if ($user->currency < $cost) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Not enough currency'),
				'message'	=> __('You do not have enough currency to mint.'),
			], 401);
		}

		// Get random building
		$randomBuilding = Building::mint();
		if (!$randomBuilding) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Minting failed'),
				'message'	=> __('Minting failed. Please try again later.'),
			], 401);
		}

		// Create user building
		$userBuilding	= UserBuilding::create([
			'user_id'		=> $user->id,
			'building_id'	=> $randomBuilding->id,
			'name'			=> generateRandomWords(2),
			'image'			=> rand(1, $randomBuilding->images)
		]);
		if ($userBuilding)
		{
			// Addiciona a transação no log
			addTransaction([
				'amount'			=> $cost,
				'type'				=> 'mint',
				'user_id'			=> $user->id,
				'status'			=> 'success',
				'user_building_id'	=> $userBuilding->id,
			]);

			// Debita o saldo do usuário
			$user->spend($cost);

			// delay pra parecer quee ta rolando algo foda
			// sleep(2);
			return $this->json([
				'success'	=> true,
				'building'	=> UserBuilding::find($userBuilding->id)->publicData(),
				'stats'  	=> [
					'currency'		=> currency($user->currency),
					'buildings'		=> $user->buildings()->count(),
					'workers'		=> $user->workers(),
					'dailyClaim'	=> currency($user->maxDailyClaim())
				]
			]);
		} else
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Minting failed'),
				'message'	=> __('Minting failed. Please try again later.'),
			], 401);
		}
    }

	public function claim(Request $request)
	{
		if (!$request->has([ 'id' ]))
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Missing required parameters.'),
			], 400);
		}

		$buildingId	= $request->input('id');
		$building	= UserBuilding::where('user_id', Auth::id())
			->where('id', $buildingId)->first();
		if (!$building)
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Building not found.'),
			], 400);
		}

		$percent	= $building->progressClaim();
		if ($percent < config('game.min_claim')) {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Claim not available yet.'),
			], 400);
		}

		if (($claim = $building->claim())) {
			$request->user()->earn($claim);

			// Addiciona a transação no log
			addTransaction([
				'amount'			=> $claim,
				'type'				=> 'claim',
				'user_id'			=> $request->user()->id,
				'status'			=> 'success',
				'user_building_id'	=> $building->id
			]);

			// delay pra parecer quee ta rolando algo foda
			// sleep(2);
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Claimed building successfully.'),
				'building'	=> $building->publicData(),
				'stats'  	=> [
					'currency'		=> currency($request->user()->currency),
					'buildings'		=> $request->user()->buildings()->count(),
					'workers'		=> $request->user()->workers(),
					'dailyClaim'	=> currency($request->user()->maxDailyClaim())
				]
			]);
		} else {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Failed to claim building.'),
			], 400);
		}
	}

	public function upgrade(Request $request)
	{
		if (!$request->has([ 'id' ]))
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Missing required parameters.'),
			], 400);
		}

		$buildingId	= $request->input('id');
		$building	= UserBuilding::where('user_id', Auth::id())
			->where('id', $buildingId)->first();
		if (!$building)
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Building not found.'),
			], 400);
		}

		$cost = $building->base->upgrade_cost;
		if ($request->user()->currency < $cost) {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Not enough currency.'),
			], 400);
		}

		if ($building->level >= config('game.max_build_level'))
		{
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('The building does not accommodate new citizens.'),
			], 400);
		}

		if ($building->upgrade()) {
			$request->user()->spend($cost);

			// Addiciona a transação no log
			addTransaction([
				'amount'			=> $cost,
				'type'				=> 'upgrade',
				'user_id'			=> $request->user()->id,
				'status'			=> 'success',
				'user_building_id'	=> $building->id
			]);

			// delay pra parecer quee ta rolando algo foda
			// sleep(2);
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Upgraded building successfully.'),
				'building'	=> $building->publicData(),
				'stats'  	=> [
					'currency'		=> currency($request->user()->currency),
					'buildings'		=> $request->user()->buildings()->count(),
					'workers'		=> $request->user()->workers(),
					'dailyClaim'	=> currency($request->user()->maxDailyClaim())
				]
			]);
		} else {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Failed to upgrade building.'),
			], 400);
		}
	}

	public function repair(Request $request)
	{
		if (!$request->has([ 'id' ]))
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Missing required parameters.'),
			], 400);
		}

		$buildingId	= $request->input('id');
		$building	= UserBuilding::where('user_id', Auth::id())
			->where('id', $buildingId)->first();
		if (!$building)
		{
			return $this->json([
				'success'	=> false,
				'title'		=> __('Error'),
				'message'	=> __('Building not found.'),
			], 400);
		}

		$cost = $building->repairCost();
		if ($request->user()->currency < $cost) {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Not enough currency.'),
			], 400);
		}

		if (!$building->needRepair()) {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('The building is not in need of repair.'),
			], 400);
		}

		if ($building->repair()) {
			$request->user()->spend($cost);

			// Addiciona a transação no log
			addTransaction([
				'amount'			=> $cost,
				'type'				=> 'repair',
				'user_id'			=> $request->user()->id,
				'status'			=> 'success',
				'user_building_id'	=> $building->id
			]);

			// delay pra parecer quee ta rolando algo foda
			// sleep(2);
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Repaired building successfully.'),
				'building'	=> $building->publicData(),
				'stats'  	=> [
					'currency'		=> currency($request->user()->currency),
					'buildings'		=> $request->user()->buildings()->count(),
					'workers'		=> $request->user()->workers(),
					'dailyClaim'	=> currency($request->user()->maxDailyClaim())
				]
			]);
		} else {
			return $this->json([
				'success'	=> false,
				'title' 	=> __('Error'),
				'message'	=> __('Failed to repair building.'),
			], 400);
		}
	}
}
