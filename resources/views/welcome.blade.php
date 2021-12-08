@extends('partials/master')
@section('content')
<div class="row">
	<div class="col-md-3">
		<div class="user-houses-stats">
			<div>
				<div class="card card-body">
					<h5 class="card-title text-center">Mint House</h5>
					<button type="button" class="btn btn-primary waves-effect waves-light text-uppercase">
						<b>New Mint</b>
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
									<h3 class="text-dark mt-1">$ <span data-plugin="counterup">58,947</span></h3>
									<p class="text-muted mb-1 text-truncate">Balance</p>
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
									<h3 class="text-dark mt-1"><span data-plugin="counterup">58,947</span></h3>
									<p class="text-muted mb-1 text-truncate">Total Houses</p>
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
									<h3 class="text-dark mt-1"><span data-plugin="counterup">58,947</span></h3>
									<p class="text-muted mb-1 text-truncate">Total Citizens</p>
								</div>
							</div>
						</div> <!-- end row-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
			@for ($i = 1; $i <= 6; $i++)
				<div class="col-md-6 col-xl-4">
					<div class="card product-box">
						<div class="card-body">
							<div class="bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 340px;">
								<img src="https://risecity.io/styles/img/casas/build{{ $i }}.png" alt="product-pic" class="img-fluid" />
							</div>

							<div class="product-info">
								<div class="row align-items-center">
									<div class="col">
										<h5 class="font-16 mt-0 sp-line-1">House {{ $i }}</h5>
										<div class="text-warning mb-2 font-13">
											@for ($i2 = 1; $i2 <= rand(1, 5); $i2++)
												<i class="fa fa-star"></i>
											@endfor
										</div>
										<h5 class="m-0">
											<span class="text-muted">{{ __('Status') }}: <span class="text-danger">Broken</span></span>
										</h5>
									</div>
									<div class="col-auto">
										<div class="product-price-tag">
											<i class="fa fa-users fa-fw"></i> 1
										</div>
									</div>
								</div> <!-- end row -->

								<div class="mt-1 text-center">
									<div class="button-list row mb-1">
										<button type="button" class="col btn btn-danger waves-effect waves-light">
											<b>{{ __('Burn') }}</b>
										</button>
										<button type="button" class="col btn btn-dark waves-effect waves-light">
											<b>{{ __('Sell') }}</b>
										</button>
										<button type="button" class="col btn btn-success waves-effect waves-light">
											<b>{{ __('Claim') }}</b>
										</button>
									</div>
									<small class="text-muted"><b>{{ __('House Valt') }}:</b> 20% ( 1.5540 Coins )</small>
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
