@extends('partials/master')
@section('content')
	<div class="row">
		<div class="col-sm-8">
			<div id="carouselExampleIndicators" class="carousel slide carousel-fade mb-2" data-bs-ride="carousel">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
					<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
					<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="https://via.placeholder.com/1280x720" class="d-block w-100" alt="Card image cap" />
					</div>
					<div class="carousel-item">
						<img src="https://via.placeholder.com/1280x720" class="d-block w-100" alt="Card image cap" />
					</div>
					<div class="carousel-item">
						<img src="https://via.placeholder.com/1280x720" class="d-block w-100" alt="Card image cap" />
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
			{{-- <div class="row">
				@for ($i = 0; $i < 3; $i++)
					<div class="col-md-4">
						<div class="card">
							<img class="card-img-top img-fluid" src="{{ asset('assets/images/small/img-' . rand(1, 5) . '.jpg') }}" alt="Card image cap" />
							<div class="card-body">
								<h5 class="card-title">
									<a href="#">Card title</a>
								</h5>
								<p class="card-text">This is a wider card with supporting text below as a
									natural lead-in to additional content. This content is a little bit
									longer.</p>
								<p class="card-text">
									<small class="text-muted">Last updated 3 mins ago</small>
								</p>
							</div>
						</div>
					</div>
				@endfor
			</div> --}}
		</div>
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-0 text-uppercase">{{ __('Welcome to') }} {{ config('app.name') }}</h4>
					<p class="my-3" style="word-break: break-word;">
						First you need deposit {{ config('game.symbol') }} from your Metamask wallet into the game,
						please go to <a href="{{ url('exchange') }}">Exchange</a> and use <b>Deposit {{ config('game.symbol') }}</b> button.
					</p>
					<p class="my-3">
						Please follow our <a href="#">Roadmap</a> for the latest game features, already available features:
					</p>
					<p class="my-3">
						Read more about <a href="#">Level - Exp Required</a>
					</p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-0 text-uppercase">{{ config('app.name') }} {{ __('Statistics') }}</h4>
					<div class="collapse pt-3 show">
						<div class="text-center">
							<div class="row mt-2">
								<div class="col-6">
									<h3 data-plugin="counterup">{{ $totalPlayers }}</h3>
									<p class="text-muted text-uppercase font-13 mb-0 text-truncate">{{ __('Players') }}</p>
								</div>
								<div class="col-6">
									<h3 data-plugin="counterup">{{ $totalHouses }}</h3>
									<p class="text-muted text-uppercase font-13 mb-0 text-truncate">{{ __('Houses') }}</p>
								</div>
								<div class="col-6">
									<h3 data-plugin="counterup">{{ $totalTransactions }}</h3>
									<p class="text-muted text-uppercase font-13 mb-0 text-truncate">{{ __('Transactions') }}</p>
								</div>
								<div class="col-6">
									<h3 data-plugin="counterup">{{ $totalWithdrawals }}</h3>
									<p class="text-muted text-uppercase font-13 mb-0 text-truncate">{{ __('Withdrawals') }}</p>
								</div>
							</div> <!-- end row -->
						</div>
					</div> <!-- end collapse-->
				</div> <!-- end card-body-->
			</div>
		</div>
	</div>
@endsection
