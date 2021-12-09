<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function index()
	{
		$this->params['page_title'] = __('My Houses');
		$this->add_bc('#',	$this->params['page_title']);
		return $this->render('dashboard.index');
	}
}
