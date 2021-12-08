@extends('partials/master')
@section('content')
<div class="row">
	<div class="col-md-6 col-xl-3">
		<div class="card card-body">
			<h5 class="card-title text-center">Special title treatment</h5>
			<a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
		</div>
	</div>
	<div class="col-md-6 col-xl-3">
		<div class="widget-rounded-circle card">
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<div class="text-ends">
							<h3 class="text-dark mt-1">$<span data-plugin="counterup">58,947</span></h3>
							<p class="text-muted mb-1 text-truncate">Total Revenue</p>
						</div>
					</div>
					<div class="col-6">
						<div class="text-end">
							<div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
								<i class="fe-heart font-22 avatar-title text-primary"></i>
							</div>
						</div>
					</div>
				</div> <!-- end row-->
			</div>
		</div> <!-- end widget-rounded-circle-->
	</div> <!-- end col-->
</div>
<div class="row">
	@for ($i = 1; $i <= 6; $i++)
		<div class="col-md-6 col-xl-3">
			<div class="card product-box">
				<div class="card-body">
					<div class="bg-light text-center">
						<img src="https://risecity.io/styles/img/casas/build33.png" alt="product-pic" class="img-fluid" />
					</div>

					<div class="product-info">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="font-16 mt-0 sp-line-1">Legendary House</h5>
								<div class="text-warning mb-2 font-13">
									@for ($i2 = 1; $i2 <= 6; $i2++)
										<i class="fa fa-star"></i>
									@endfor
								</div>
								<h5 class="m-0">
									<span class="text-muted">{{ __('Status') }}: <span class="text-success">Good</span></span>
								</h5>
							</div>
							<div class="col-auto">
								<div class="product-price-tag">
									<i class="fa fa-users fa-fw"></i> 1
								</div>
							</div>
						</div> <!-- end row -->

						<div class="d-grid mt-1">
							<button type="button" class="btn btn-danger waves-effect waves-light" disabled>
								<b>{{ __('Next Claim') }}:</b> 2021-12-10 at 02:10:05
							</button>
						</div>

						<div class="row text-center">
							<div class="col-4">
								<div class="mt-3">
									<h4>$ 43</h4>
									<p class="mb-0 text-muted text-truncate">{{ __('Daily Claim') }}</p>
								</div>
							</div>
							<div class="col-4">
								<div class="mt-3">
									<h4>$ 322</h4>
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
@endsection
