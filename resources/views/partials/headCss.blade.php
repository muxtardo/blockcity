<!-- Sweet Alert-->
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App CSS -->
@if (config('app.locale') !== 'he')
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
	<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

	<link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
	<link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
@else
	<link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
	<link href="{{ asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

	<link href="{{ asset('assets/css/bootstrap-dark-rtl.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
	<link href="{{ asset('assets/css/app-dark-rtl.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
@endif

<!-- icons -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Custom CSS -->
<link href="{{ asset('assets/css/game.css') }}?c={{ filemtime(public_path('assets/css/game.css')) }}" rel="stylesheet" type="text/css" />
@yield('css')
