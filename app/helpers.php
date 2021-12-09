<?php

use Carbon\Carbon;

if (!function_exists('currency')) {
	function currency($value, $decimals = 4, $decimalPoint = '.', $thousandSeparator = '') {
		return round($value, $decimals);
		// return number_format($value, $decimals, $decimalPoint, $thousandSeparator);
	}
}

if (!function_exists('oracleConvert')) {
	function oracleConvert($value, $raw = true) {
		$tokenPrice	= 1;	// 1 token = 1 USD
		$value		= $value / $tokenPrice;

		return !$raw ? currency($value) : $value;
	}
}

if (!function_exists('coinToOracle')) {
	function coinToOracle($value) {

	}
}

if (!function_exists('oracleToCoin')) {
	function oracleToCoin($value) {

	}
}

if (!function_exists('percent')) {
	function percent($value, $max, $width = 100) {
		if ($value == 0 || $max == 0) {
			return 0;
		}

		$percent = ($value / $max) * $width;
		return $percent > $width ? $width : $percent;
	}
}

if (!function_exists('percentf')) {
	function percentf($value, $percent) {
		if ($value == 0 || $percent == 0) {
			return 0;
		}

		$percent	/=	100;
		$percent	=	$value * $percent;

		return round($percent, 4);
	}
}

if (!function_exists('between')) {
	function between($value, $start, $end) {
		return $value >= $start && $value <= $end;
	}
}

if (!function_exists('get_chance')) {
	function get_chance() {
		return rand(1, 400) / 4;
	}
}

if (!function_exists('has_chance')) {
	function has_chance($val) {
		$rnd = get_chance();
		return $rnd <= $val ? true : false;
	}
}

if (!function_exists('array_random_key')) {
	function array_random_key($arr) {
		$keys	= array_keys($arr);

		return $keys[floor(rand(0, sizeof($keys) - 1))];
	}
}

if (!function_exists('array_random_item')) {
	function array_random_item($arr) {
		return $arr[array_random_key($arr)];
	}
}

if (!function_exists('frand')) {
	function frand($min = 0, $max = null) {
		if (is_null($max)) {
			$max	= getrandmax();
		}

		return $min + ((float)rand()/(float)getrandmax()) * $max;
	}
}

if (!function_exists('format_time')) {
	function format_time($seconds) {
		$hours		= 0;
		$minutes	= 0;

		while ($seconds >= 3600) {
			++$hours;
			$seconds -= 3600;
		}

		while ($seconds >= 60) {
			++$minutes;
			$seconds -= 60;
		}

		return [
			'hours'		=> sprintf("%02s", $hours),
			'minutes'	=> sprintf("%02s", $minutes),
			'seconds'	=> sprintf("%02s", $seconds),
			'string'	=> sprintf("%02s", $hours) . ":" . sprintf("%02s", $minutes) . ":" . sprintf("%02s", $seconds)
		];
	}
}

if (!function_exists('highamount')) {
	function highamount($number, $decimals = 0) {
		return @number_format($number, $decimals, '.', ',');
	}
}

if (!function_exists('random_str')) {
	function random_str($length) {
		$letters	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str		= "";
		for ($i = 1; $i <= $length; $i++) {
			$rand = rand(0, strlen($letters) - 1);
			$str .= $letters[$rand];
		}

		return $str;
	}
}

if (!function_exists('timeLeft')) {
	function timeLeft($date) {
		$seconds	= (Carbon::parse($date)->timestamp - Carbon::now()->timestamp) - 1;

		$days		=	floor($seconds / 86400);
		$seconds	%=	86400;

		$hours		=	floor($seconds / 3600);
		$seconds	%=	3600;

		$minutes	=	floor($seconds / 60);
		$seconds	%=	60;

		$data		= [];
		if ($days > 0) {
			$data[]	= $days . ' days';
		}
		$data[]	= $hours . ' hours';
		$data[]	= $minutes . ' minutes';
		$data[]	= $seconds . ' seconds';

		return join(', ', $data);
	}
}

if (!function_exists('reverseThemeColor')) {
	function reverseThemeColor(){
		if (config('app.theme') == 'light') {
			return 'dark';
		}
		return 'light';
	}
}

if (!function_exists('generateRandomWords')) {
	function generateRandomWords($numberWords) {
		$words = file(__DIR__ . '/../resources/words.txt');
		return ucwords(implode(" ", array_map(function ($index) use ($words) { return $words[$index];  }, array_rand($words, $numberWords))));
	}
}
