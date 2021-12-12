<!-- Sweet Alert-->
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App CSS -->
@if (config('app.locale') !== 'he')
	<link href="{{ asset('assets/css/config/creative/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
	<link href="{{ asset('assets/css/config/creative/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

	<link href="{{ asset('assets/css/config/creative/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
	<link href="{{ asset('assets/css/config/creative/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
@else
	<link href="{{ asset('assets/css/config/creative/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
	<link href="{{ asset('assets/css/config/creative/app-rtl.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

	<link href="{{ asset('assets/css/config/creative/bootstrap-dark-rtl.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
	<link href="{{ asset('assets/css/config/creative/app-dark-rtl.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
@endif

<!-- icons -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Custom CSS -->
<link href="{{ asset('assets/css/custom.css') }}?c={{ filemtime(public_path('assets/css/custom.css')) }}" rel="stylesheet" type="text/css" />
@yield('css')
