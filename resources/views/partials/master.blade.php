<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ config('app.locale') == 'he' ? 'rtl' : 'ltr' }}">
<head>
	@include("partials/titleMeta", [
		"title"	=> isset($page_title) ? $page_title : false
	])

	@include('partials/headCss')
</head>
<!-- body start -->
<body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": false}'>
	<!-- Begin page -->
	<div id="wrapper">
		@include('partials/menu')

		<div class="content-page">
			<div class="content {{ Auth::check() ? 'with-user' : '' }}">
				<!-- Start Content-->
				<div class="container-fluid">
					<!-- start page title -->
					@include("partials/pageTitle", [
						"bc"		=> isset($bc) ? $bc : false,
						"pageTitle"	=> isset($page_title) ? $page_title : false
					])
					<!-- end page title -->
					@if (in_array(config('app.locale'), ['es', 'fr', 'he', 'pt-BR', 'zh-CN']))
						<div class="alert alert-info bg-info text-white border-0" role="alert">
							The translation was automatically generated,
							if something was mistranslated and you are interested in helping to improve the translation,
							please contact us!
						</div>
					@endif
					@yield('content')
				</div><!-- container -->
			</div><!-- content -->

			@include('partials/footer')
		</div>
	</div>
	<!-- END wrapper -->

	<!-- Base Js -->
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.js') }}"></script>

	<!-- Plugins js -->
	<script src="{{ asset('assets/js/web3.min.js') }}"></script>
	<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
	<script src="{{ asset('assets/libs/maskMoney/jquery.maskMoney.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.4.5/ethers.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
	@yield('js-libs')

	<!-- App Variables -->
	<script type="text/javascript">
		const domainName	= '{{ Request::server('SERVER_NAME') }}';
		const site_url		= '{{ url('/') }}';
		const current_page 	= '{{ Route::currentRouteName() }}';
		const gameContract	= '{{ config('game.contract') }}';
		const walletPagos	= '{{ config('game.wallet_pagos') }}';
		const tokenParams	= {
			type: 'ERC20',
			options: {
				address:	gameContract,
				symbol:		'{{ config('game.symbol') }}',
				decimals:	4,
				image:		'{{ asset('favicon.ico') }}'
			}
		};

		@if (Auth::check())
			// Set user variables
			let	userWallet		= '{{ Auth::user()->wallet }}';
			let	userTransaction	= {{ Auth::user()->getPendingTransaction() ? Auth::user()->getPendingTransaction() : 'false' }};
		@endif

		const I18n = {!! $locale !!}
	</script>
	@yield('js')

	<!-- Game Js -->
	<script type="text/javascript" src="{{ asset('assets/js/game.js') }}?c={{ filemtime(public_path('assets/js/game.js')) }}"></script>
	@yield('script')
</body>
</html>
