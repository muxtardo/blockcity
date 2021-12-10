<!DOCTYPE html>
<html lang="en">
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
	<script src="{{ asset('assets/js/app.js') }}"></script>

	<!-- Custom Js -->
	<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<!--
	<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
	-->
	<script src="{{ asset('assets/js/web3.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.4.5/ethers.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
	@yield('js-libs')

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

		@if (Auth::check())
			let	userWallet		= '{{ Auth::user()->wallet }}';
			let	userTransaction	= '{{ Auth::user()->getPendingTransaction() }}';
			$(document).ready(function() {
				if (userTransaction) {
					checkTransaction(userTransaction);
				}
			});
		@endif

		const axiosInstance	= axios.create({
			baseURL: site_url,
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}',
			},
		});

		function enableTooltip(){
			const tooltipTriggerList	= [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			const tooltipList			= tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl, {
					html: true
				})
			})
		}
	</script>
	@yield('js')

	<script type="text/javascript" src="{{ asset('assets/js/global.js') }}?c={{ filemtime(public_path('assets/js/global.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/metamask.js') }}?c={{ filemtime(public_path('assets/js/metamask.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/users.js') }}?c={{ filemtime(public_path('assets/js/modules/users.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/buildings.js') }}?c={{ filemtime(public_path('assets/js/modules/buildings.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/inventory.js') }}?c={{ filemtime(public_path('assets/js/modules/inventory.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/quests.js') }}?c={{ filemtime(public_path('assets/js/modules/quests.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/marketplace.js') }}?c={{ filemtime(public_path('assets/js/modules/marketplace.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/modules/exchange.js') }}?c={{ filemtime(public_path('assets/js/modules/exchange.js')) }}"></script>

	@yield('script')
</body>
</html>
