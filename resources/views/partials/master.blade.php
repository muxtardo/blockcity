<!DOCTYPE html>
<html lang="en">
<head>
	@include("partials/titleMeta", [
		"title"	=> "Welcome"
	])

	@include('partials/headCss')
</head>
<!-- body start -->
<body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
	<!-- Begin page -->
	<div id="wrapper">
		@include('partials/menu')

		<!-- ============================================================== -->
		<!-- Start Page Content here -->
		<!-- ============================================================== -->
		<div class="content-page">
			<div class="content">
				<!-- Start Content-->
				<div class="container-fluid">
					<!-- start page title -->
					@include("partials/pageTitle", [
						"subtitle"	=> "Extras Pages",
						"title"		=>"Starter"
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

	@include('partials/rightSidebar')

	<!-- Vendor Js -->
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>

	<!-- App Js -->
	<script src="{{ asset('assets/js/app.min.js') }}"></script>

	<!-- Custom Js -->
	@yield('script')
	@yield('script-bottom')
</body>
</html>
