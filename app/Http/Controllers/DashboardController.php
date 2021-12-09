<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$userBuildings	= $request->user()->buildings();
		$this->params['buildings']		= $userBuildings
			->join('buildings', 'buildings.id', '=', 'user_buildings.building_id')
			->orderBy('user_buildings.last_claim_at', 'asc')
			->orderBy('buildings.rarity', 'desc')
			->orderBy('user_buildings.highlight', 'desc')
			->paginate(6);
		$this->params['totalBuildings']	= $userBuildings->count();

		$this->params['page_title'] = __('My Houses');
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('dashboard.index');
	}
}
