<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public 	$params	= [];
	private	$bc		= [];

	public function __construct()
	{
		$contents = '{}';
		if (config('app.locale') != 'en')
			$contents = file_get_contents(lang_path(config('app.locale') . '.json'));

		$this->params['locale']	= $contents;
	}

	protected function add_bc($link, $page)
    {
        $this->bc[] = [
            'link'  => $link,
            'page'  => $page
        ];
    }

	public function render($view)
	{
		$this->params['bc']	= $this->bc;
		return view($view,	$this->params);
	}

	public function json($data = [], $statusCode = 200)
	{
		return response()->json($data, $statusCode);
	}
}
