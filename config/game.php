<?php

return [
	'api_web3_url'			=> (string) env('API_WEB3_URL', 		'http://localhost:3000'),
	'presale'				=> (bool)	env('PRESALE',				false),
	'testnet' 				=> (bool)	env('IS_TESTNET',			true),
	'symbol'				=> (string)	env('CONTRACT_SYMBOL',		'BKC'),
	'contract'				=> (string)	env('CONTRACT_ADDRESS',		'0x0000000000000000000000000000000000000000'),
	'wallet_pagos'			=> (string)	env('WALLET_PAGOS',			'0x0000000000000000000000000000000000000000'),
	'min_claim'				=> (int)	env('MIN_CLAIM',			30),
	'max_build_level'		=> (int)	env('MAX_BUILD_LEVEL',		3),
	'withdraw'				=> (bool)	env('WITHDRAW',				false),
	'min_withdraw'			=> (float)	env('MIN_WITHDRAW',			10),
	'max_withdraw'			=> (float)	env('MAX_WITHDRAW',			5000),
];
