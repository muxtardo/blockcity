<!-- Sweet Alert-->
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App CSS -->
<link href="{{ asset('assets/css/config/creative/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{ asset('assets/css/config/creative/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

@if (config('app.theme') == 'light')
    <link href="{{ asset('assets/css/config/creative/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-stylesheet" />
    <link href="{{ asset('assets/css/config/creative/app.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />
@else
    <link href="{{ asset('assets/css/config/creative/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
    <link href="{{ asset('assets/css/config/creative/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
@endif

<!-- icons -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

<!-- Custom CSS -->
@yield('css')
