<!-- Topbar Start -->
<div class="navbar-custom">
	<div class="container-fluid">
		<ul class="list-unstyled topnav-menu float-end mb-0">
			<li class="dropdown d-none d-lg-inline-block">
				<a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
					<i class="fe-maximize noti-icon"></i>
				</a>
			</li>

			<li class="dropdown d-none d-lg-inline-block topbar-dropdown">
				<a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
					<img src="{{ asset('assets/images/flags/us.jpg') }}" alt="user-image" height="16">
				</a>
				<div class="dropdown-menu dropdown-menu-end">
					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item">
						<img src="{{ asset('assets/images/flags/spain.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
					</a>
					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item">
						<img src="{{ asset('assets/images/flags/russia.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
					</a>
				</div>
			</li>

			<li class="dropdown notification-list topbar-dropdown">
				<span class="nav-link nav-user me-0">
					<span class="pro-user-name ms-1">
						{{ Auth::user()->getUsername() }}
					</span>
				</span>
			</li>

			<li class="dropdown notification-list">
				<a href="{{ url('auth/logout') }}" class="nav-link right-bar-toggle waves-effect waves-light">
					<i class="fe-log-out noti-icon"></i>
				</a>
			</li>

		</ul>

		<!-- LOGO -->
		<div class="logo-box">
			<a href="{{ url('/') }}" class="logo text-center">
				<span class="logo-sm">
					<span class="logo-lg-text-light">U</span>
				</span>
				<span class="logo-lg">
					<span class="logo-lg-text-light">{{ config('app.name') }}</span>
				</span>
			</a>
		</div>

		<ul class="list-unstyled topnav-menu topnav-menu-left m-0">
			<li>
				<button class="button-menu-mobile waves-effect waves-light">
					<i class="fe-menu"></i>
				</button>
			</li>

			<li>
				<!-- Mobile menu toggle (Horizontal Layout)-->
				<a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
					<div class="lines">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</a>
				<!-- End mobile menu toggle-->
			</li>
		</ul>
		<div class="clearfix"></div>
	</div>
</div>
<!-- end Topbar -->
