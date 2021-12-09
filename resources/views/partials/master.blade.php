<!DOCTYPE html>
<html lang="en">
<head>
	@include("partials/titleMeta", [
		"title"	=> isset($page_title) ? $page_title : false
	])

	@include('partials/headCss')
</head>
<!-- body start -->
<body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "dark", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "{{ reverseThemeColor() }}"}, "showRightSidebarOnPageLoad": true}'>
	<!-- Begin page -->
	<div id="wrapper">
		@include('partials/menu')

		<!-- ============================================================== -->
		<!-- Start Page Content here -->
		<!-- ============================================================== -->
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
					@yield('content')
				</div> <!-- container -->

			</div> <!-- content -->

			@include('partials/footer')
		</div>
		<!-- ============================================================== -->
		<!-- End Page content -->
		<!-- ============================================================== -->
	</div>
	<!-- END wrapper -->

	<!-- Base Js -->
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.min.js') }}"></script>

	<!-- Custom Js -->
	<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.4.5/ethers.umd.min.js"></script>
	<script type="text/javascript">
		const domainName	= '{{ Request::server('SERVER_NAME') }}';
		const site_url		= '{{ url('/') }}';
		const isTestnet		= {{ config('game.testnet') ? 'true' : 'false' }};
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
		const axiosInstance = axios.create({
			baseURL: site_url,
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}',
			},
		});
	</script>
	<script type="application/javascript" src="{{ asset('assets/js/global.js') }}"></script>
	@yield('script')
	@yield('script-bottom')
</body>
</html>
