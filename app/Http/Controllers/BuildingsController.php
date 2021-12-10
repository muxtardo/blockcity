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
			'counters'	=> [
				'buildings'	=> $userBuildings->count(),
				'workers'	=> $request->user()->workers(),
			]
		]);
	}

    public function mint()
    {
		sleep(2);

		$user = Auth::user();

		if ($user->currency < config('game.mint_cost')) {
			return $this->json([
				'success'	=> false,
				'title'		=> __('Not enough currency'),
				'message'	=> __('You do not have enough currency to mint.'),
			]);
		}

		$user->spend(config('game.mint_cost'));

		$randomBuilding = Building::mint();

        $userBuilding = UserBuilding::create([
            'user_id'		=> Auth::id(),
            'building_id'	=> $randomBuilding->id,
            'name'			=> generateRandomWords(2),
            'image'			=> rand(1, $randomBuilding->images),
        ]);

        return response()->json([
            'success'	=> true,
			'building'	=> UserBuilding::find($userBuilding->id)->publicData(),
			'currency'  => currency($user->currency),
        ]);
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
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Claimed building successfully.'),
				'currency'	=> currency($request->user()->currency),
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
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Upgraded building successfully.'),
				'currency'	=> currency($request->user()->currency),
				'building'	=> $building->publicData(),
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
			return $this->json([
				'success'	=> true,
				'title' 	=> __('Success'),
				'message'	=> __('Repaired building successfully.'),
				'currency'	=> currency($request->user()->currency),
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
