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
		$userBuildings				= $request->user()->buildings();
		$this->params['buildings']	= $userBuildings->select('buildings.*', 'user_buildings.*')
				->join('buildings', 'buildings.id', '=', 'user_buildings.building_id')
				->orderBy('user_buildings.last_claim_at', 'asc')
				->orderBy('buildings.rarity', 'desc')
				->orderBy('user_buildings.highlight', 'desc')->paginate(6);

		return $this->json([
			'success'	=> true,
			'content'	=> $this->render('buildings.list')->render(),
			'counters'	=> [
				'buildings'	=> $userBuildings->count(),
				'workers'	=> $request->user()->workers(),
			]
		]);
	}

    public function mint()
    {
        $randomBuilding = Building::mint();

        $userBuilding = UserBuilding::create([
            'user_id'		=> Auth::id(),
            'building_id'	=> $randomBuilding->id,
            'name'			=> generateRandomWords(2),
            'image'			=> rand(1, $randomBuilding->images),
        ]);

        return response()->json([
            'success'	=> true,
            'userItem'	=> $userBuilding,
            'image'		=> $userBuilding->getImage(true),
            'name'		=> $userBuilding->name,
            'rarity'	=> $randomBuilding->rarity,
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
			return response()->json([
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
}
