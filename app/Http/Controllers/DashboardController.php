<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$userBuildings	= $request->user()->buildings();
		$this->params['buildings']		= $userBuildings->select('buildings.*', 'user_buildings.*')
			->join('buildings', 'buildings.id', '=', 'user_buildings.building_id')
			->orderBy('user_buildings.last_claim_at', 'asc')
			->orderBy('buildings.rarity', 'desc')
			->orderBy('user_buildings.highlight', 'desc')
			->simplePaginate(6);
		$this->params['totalBuildings']	= $userBuildings->count();

		$this->params['page_title'] = __('My Houses');
		$this->params['filters'] = BuildingsController::getFilters();
		$this->params['filterDefault'] = BuildingsController::getFilterDefault();
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('dashboard.index');
	}
}
