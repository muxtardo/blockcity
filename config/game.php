<?php

$prefix = env('IS_TESTNET') ? 'TESTNET_' : 'MAINNET_';

return [
	'api_web3_url'			=> (string) env('API_WEB3_URL', 				'http://localhost:3000'),
	'presale'				=> (bool)	env('PRESALE',						false),
	'testnet' 				=> (bool)	env('IS_TESTNET',					true),
	'symbol'				=> (string)	env($prefix . 'CONTRACT_SYMBOL',	'BCT'),
	'contract'				=> (string)	env($prefix . 'CONTRACT_ADDRESS',	'0x0000000000000000000000000000000000000000'),
	'wallet_pagos'			=> (string)	env($prefix . 'WALLET_PAGOS',		'0x0000000000000000000000000000000000000000'),
	'min_claim'				=> (int)	env('MIN_CLAIM',					30),
	'max_build_level'		=> (int)	env('MAX_BUILD_LEVEL',				3),
	'withdraw'				=> (bool)	env('WITHDRAW',						false),
	'min_withdrawal'		=> (float)	env('MIN_WITHDRAWAL',				10),
	'max_withdrawal'		=> (float)	env('MAX_WITHDRAWAL',				5000),
	'mint_cost'				=> (float)	env('MINT_COST',					100)
];
