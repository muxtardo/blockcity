<!-- Topbar Start -->
<div class="navbar-custom">
	<div class="container-fluid">
		<ul class="list-unstyled topnav-menu float-end mb-0">
			<li class="dropdown d-none d-lg-inline-block">
				<a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
					<i class="fe-maximize noti-icon"></i>
				</a>
			</li>
			<li class="dropdown d-none d-lg-inline-block">
				<span class="nav-link right-bar-toggle waves-effect waves-light change-theme">
					<i class="fe-moon noti-icon"></i>
				</a>
			</li>
			<li class="dropdown d-nonse d-lg-inline-blsock topbar-dropdown">
				<a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
					<img src="{{ asset('assets/images/flags/' . App::currentLocale() . '.jpg') }}" alt="user-image" height="16">
				</a>
				<div class="dropdown-menu dropdown-menu-end">
					@foreach (config('app.locales') as $localeCode => $language)
						<a class="dropdown-item" href="{{ url('locale/' . $localeCode) }}">
							<img src="{{ asset('assets/images/flags/' . $localeCode . '.jpg') }}" alt="user-image" class="me-1" height="12">
							{{ $language }}
						</a>
					@endforeach
				</div>
			</li>
			@auth
				<li class="dropdown notification-list topbar-dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
						<i class="fe-bell noti-icon"></i>
						@if (Auth::user()->notifications())
							<span class="badge bg-danger rounded-circle noti-icon-badge">
								<i class="mdi mdi-exclamation-thick"></i>
							</span>
						@endif
					</a>
					<div class="dropdown-menu dropdown-menu-end dropdown-lg">
						<!-- item-->
						<div class="dropdown-item noti-title">
							<h5 class="m-0">{{ __('Notifications') }}</h5>
						</div>

						<div class="noti-scroll" data-simplebar>
							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-success">
									<i class="fe-home"></i>
								</div>
								<p class="notify-details">
									You have 3 houses to claim!
									<small class="text-muted">Alert from {{ config('app.name') }}</small>
								</p>
							</a>
						</div>
					</div>
				</li>
			@endauth

			<li class="dropdown notification-list topbar-dropdown">
				<span class="nav-link nav-user me-0">
					@if (Auth::guest())
						<button type="button" class="btn btn-dark btn-sm user-auth">
							<span>
								<svg width="19" style="margin-top: -2px;" viewBox="0 0 24 24" fill="none">
									<path d="M8.49 3.5L1.492.5l-1 3 2 8.5 18.995-.5 2-8-1.5-3-6.499 3H8.49z" fill="#763D16"></path>
									<path d="M6.99 12h10.998v7H6.99v-7z" fill="#333"></path>
									<path fill="#333" d="M7.99 17h5.998v3H7.99z"></path>
									<path d="M1.581 10.664a.609.609 0 0 1 .126.585l-1.484 4.59a.517.517 0 0 0 0 .356l1.296 4.445c.104.334.438.522.752.417l4.452-1.231c.188-.063.397 0 .564.125l.773.626c.021.021.021.021.042.021l1.923 1.336c.104.062.23.104.355.104H13.6c.125 0 .25-.042.355-.104l1.923-1.336c.02 0 .02-.02.042-.02l.773-.627a.663.663 0 0 1 .564-.125l4.452 1.231c.335.084.669-.104.753-.417l1.295-4.445a.517.517 0 0 0 0-.355l-1.483-4.591a.599.599 0 0 1 .125-.585l1.588-7.116a.536.536 0 0 0-.02-.313L23.024.417c-.105-.334-.48-.5-.815-.375l-6.94 2.587a.744.744 0 0 1-.208.042H8.917c-.083 0-.146-.02-.209-.042L1.77.042c-.334-.126-.71.041-.815.375l-.92 2.818a.524.524 0 0 0-.02.313l1.567 7.116zm12.415 3.59l.522-1.085c.063-.126.23-.188.356-.126l1.254.564c.23.104.209.438-.042.522l-1.756.521a.285.285 0 0 1-.334-.396zm-.104-7.534c-.126-.105-.126-.313.02-.397l7.9-5.405a.252.252 0 0 1 .377.125l.982 2.692c.021.042.021.084 0 .147l-1.61 6.198a.226.226 0 0 1-.292.146l-4.347-1.169c-.042 0-.063-.02-.084-.042L13.891 6.72zm-3.825 12.814l.146-1.21c0-.063.042-.126.105-.168l.292-.208c.042-.021.084-.042.126-.042h2.466c.042 0 .104.02.125.042l.293.208c.063.042.083.105.104.167l.126 1.21a.243.243 0 0 1-.23.272h-3.344c-.105 0-.21-.125-.21-.271zM7.85 13.607l1.254-.564a.27.27 0 0 1 .356.126l.522 1.085c.105.208-.104.438-.334.375l-1.777-.521c-.23-.063-.25-.397-.02-.501zM.808 3.86c-.021-.042 0-.105 0-.146l.982-2.672c.063-.146.251-.208.376-.125l7.9 5.405a.244.244 0 0 1 .022.397L7.14 9.015a.159.159 0 0 1-.084.042L2.71 10.226a.226.226 0 0 1-.293-.146L.807 3.86z" fill="#F36D34"></path>
								</svg>
								{{ __('Connect with MetaMask') }}
							</span>
						</button>
					@else
						<button type="button" class="btn btn-success btn-sm">
							{{-- <span class="pro-user-name ms-1"> --}}
								{{ Auth::user()->getUsername() }}
							{{-- </span> --}}
						</button>
					@endif
				</span>
			</li>

			@auth
				<li class="dropdown notification-list">
					<a href="{{ url('logout') }}" class="nav-link right-bar-toggle waves-effect waves-light">
						<i class="fe-log-out noti-icon"></i>
					</a>
				</li>
			@endauth
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

		@auth
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
		@endauth
		<div class="clearfix"></div>
	</div>
</div>
<!-- end Topbar -->
