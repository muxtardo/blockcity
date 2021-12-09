@include('partials/topbar')
@if (Auth::check())
	@include('partials/horizontal')
@endif
