@extends('partials/master')
@section('script')
<script>
	// bootstrap modal instance
	const myModal = new bootstrap.Modal(document.getElementById('myModal')); //new Modal(document.getElementById('myModal'));

	function showStarsAnimated() {
		$(".stars").hide().removeClass('d-none').each(function(idx) {

			setTimeout(() => $(this).fadeIn(500), (idx+1) * 600);

		});
	}

	$("#new-mint").click(function() {
		axiosInstance.post('buyHouse')
			.then((res) => {
				$("#buyHouse-name").text(res.data.userItem.name);
				myModal.show();
				showStarsAnimated();
			})

	});

</script>
@endsection
@section('content')

<div class="modal fade" id="myModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div  class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<!-- Modal content-->
				<div class="card-body product-box">
					<div class="bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 340px;">
						<img src="https://risecity.io/styles/img/casas/build{{ rand(1, 52) }}.png" id="buyHouse-image" alt="product-pic" class="img-fluid" />
					</div>
					<div class="product-info">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="font-16 mt-0 sp-line-1" id="buyHouse-name"></h5>
								<div class="text-warning mb-2 font-13" id="buyHouse-stars">
									{!! Str::repeat('<i class="fa fa-star d-none stars"></i>', rand(1, 5)) !!}
								</div>
								<h5 class="m-0">
									<span class="text-muted">Produção: <span class="green">Good</span></span>
								</h5>
							</div>
							<div class="col-auto">
								<div class="product-price-tag">
									<i class="fa fa-users fa-fw"></i> 1
								</div>
							</div>
						</div> <!-- end row -->
					</div> <!-- end product info-->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	@auth
	<div class="col-lg-3">
		<div class="user-houses-stats mb-2">
			<div>
				<div class="card card-body">
					<h5 class="card-title text-center">{{ __('Mint House') }}</h5>
					<p class="text-muted text-center">{{ __('Click on the button below to purchase a new home.') }}</p>
					<button type="button" id="new-mint" class="btn btn-primary waves-effect waves-light text-uppercase">
						<b>{{ __('New Mint') }}</b>
					</button>
				</div>
				<div class="widget-rounded-circle card">
					<div class="card-body">
						<div class="row">
							<div class="col-6">
								<div class="avatar-lg rounded-circle bg-soft-success border-success border">
									<i class="fe-dollar-sign font-22 avatar-title text-success"></i>
								</div>
							</div>
							<div class="col-6">
								<div class="text-end">
									<h3 class="text-dark mt-1"><span data-plugin="counterup">{{ currency(Auth::user()->currency) }}</span></h3>
									<p class="text-muted mb-1 text-truncate">{{ __('Coins') }}</p>
								</div>
							</div>
						</div> <!-- end row-->
					</div>
				</div>
				<div class="widget-rounded-circle card">
					<div class="card-body">
						<div class="row">
							<div class="col-6">
								<div class="avatar-lg rounded-circle bg-soft-info border-info border">
									<i class="fe-home font-22 avatar-title text-info"></i>
								</div>
							</div>
							<div class="col-6">
								<div class="text-end">
									<h3 class="text-dark mt-1"><span data-plugin="counterup">{{ sizeof(Auth::user()->houses()) }}</span></h3>
									<p class="text-muted mb-1 text-truncate">{{ __('Total Houses') }}</p>
								</div>
							</div>
						</div> <!-- end row-->
					</div>
				</div>
				<div class="widget-rounded-circle card">
					<div class="card-body">
						<div class="row">
							<div class="col-6">
								<div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
									<i class="fe-users font-22 avatar-title text-primary"></i>
								</div>
							</div>
							<div class="col-6">
								<div class="text-end">
									<h3 class="text-dark mt-1"><span data-plugin="counterup">{{ Auth::user()->sumLevel() }}</span></h3>
									<p class="text-muted mb-1 text-truncate">{{ __('Total Citizens') }}</p>
								</div>
							</div>
						</div> <!-- end row-->
					</div>
				</div>
			</div>
		</div>
	</div>
	@endauth
	<div class="col-lg-9">
		<div class="alert alert-primary bg-primary text-white border-0" role="alert">
			This is a <strong>primary</strong> alert—check it out!
		</div>
		<div class="row">
			@for ($i = 1; $i <= 6; $i++)
				<div class="col-md-6 col-xl-4">
					<div class="card ribbon-box">
						<div class="ribbon-two ribbon-two-blue text-uppercase"><span>{{ __('New') }}</span></div>
						<div class="card-body product-box">
							<div class="bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 340px;">
								<img src="https://risecity.io/styles/img/casas/build{{ rand(1, 52) }}.png" alt="product-pic" class="img-fluid" />
							</div>

							<div class="product-info">
								<div class="row align-items-center">
									<div class="col">
										<h5 class="font-16 mt-0 sp-line-1">House House House House House {{ $i }}</h5>
										<div class="text-warning mb-2 font-13">
											{!! Str::repeat('<i class="fa fa-star stars"></i>', rand(1, 5)) !!}
										</div>
										<h5 class="m-0">
											@php
												$randomStatus = \App\Models\ItemStatuse::inRandomOrder()->first()
											@endphp
											<span class="text-muted">{{ __('Production') }}: <span class="{{ $randomStatus->color }}">{{ __($randomStatus->name) }}</span></span>
										</h5>
									</div>
									<div class="col-auto">
										<div class="product-price-tag">
											<i class="fa fa-users fa-fw"></i> {{ rand(1, 3) }}
										</div>
									</div>
								</div> <!-- end row -->

								<div class="mt-1 text-center">
									<div class="button-list row mb-1">
										<button type="button" class="col btn btn-danger waves-effect waves-light">
											<b>{{ __('Repair') }}</b>
										</button>
										<button type="button" class="col btn btn-dark waves-effect waves-light">
											<b>{{ __('Sell') }}</b>
										</button>
										<button type="button" class="col btn btn-success waves-effect waves-light">
											<b>{{ __('Claim') }}</b>
										</button>
									</div>
									<small class="text-muted"><b>{{ __('House Vault') }}:</b> 20% ( 1.5540 {{ __('Coins') }} )</small>
								</div>

								<div class="progress position-relative" style="height: 20px;">
									<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 20%"></div>
								</div>

								<div class="row text-center">
									<div class="col-4">
										<div class="mt-3">
											<h4>$ 7.77</h4>
											<p class="mb-0 text-muted text-truncate">{{ __('Daily Claim') }}</p>
										</div>
									</div>
									<div class="col-4">
										<div class="mt-3">
											<h4>$ 7.77</h4>
											<p class="mb-0 text-muted text-truncate">{{ __('Last Claim') }}</p>
										</div>
									</div>
									<div class="col-4">
										<div class="mt-3">
											<h4>$ 468</h4>
											<p class="mb-0 text-muted text-truncate">{{ __('Total Claim') }}</p>
										</div>
									</div>
								</div>
							</div> <!-- end product info-->
						</div>
					</div> <!-- end card-->
				</div> <!-- end col-->
			@endfor
		</div>
	</div>
</div>
@endsection
