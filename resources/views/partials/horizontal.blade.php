<div class="topnav">
	<div class="container-fluid">
		<nav class="navbar navbar-light navbar-expand-lg topnav-menu">
			<div class="collapse navbar-collapse justify-content-center" id="topnav-menu-content">
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('/') }}" id="topnav-home">
							<i class="fe-airplay me-1"></i> {{ __('Home') }}
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('dashboard') }}" id="topnav-dashboard">
							<i class="fe-home me-1"></i> {{ __('My Houses') }}
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('inventory') }}" id="topnav-inventory">
							<i class="fe-box me-1"></i> {{ __('Inventory') }}
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('quests') }}" id="topnav-quests">
							<i class="fe-check-square me-1"></i> {{ __('Daily Quests') }}
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('marketplace') }}" id="topnav-marketplace">
							<i class="fe-shopping-cart me-1"></i> {{ __('Marketplace') }}
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link arrow-none" href="{{ url('exchange') }}" id="topnav-exchange">
							<i class="fe-refresh-cw me-1"></i> {{ __('Exchange') }}
						</a>
					</li>
				</ul> <!-- end navbar-->
			</div> <!-- end .collapsed-->
		</nav>
	</div> <!-- end container-fluid -->
</div> <!-- end topnav-->
