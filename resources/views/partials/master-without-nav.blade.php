<!DOCTYPE html>
<html lang="en">
<head>
	@include("partials/titleMeta", [
		"title"	=> isset($page_title) ? $page_title : false
	])

	@include('partials/headCss')
</head>
<!-- body start -->
<body class="loading {{ isset($addBody) ? $addBody : '' }}">
	<div class="account-pages mt-5 mb-5">
		<div class="container">
			@yield('content')
		</div><!-- end container -->
	</div><!-- end page -->
	<footer class="footer footer-alt">
		{{ date('Y') }} &copy; <b>{{ config('app.name') }}</b> - {{ __('All rights reserved') }}.
	</footer>

	<!-- Base Js -->
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.min.js') }}"></script>
</body>
</html>
