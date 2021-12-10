@extends('partials/master')

@section('content')
	<div class="modal fade show" id="myModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div  class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					<!-- Modal content-->
					<div class="card-body product-box">
						<div class="bg-sky text-center d-flex align-items-center justify-content-center" style="min-height: 340px; position: relative;">
							<div class="stars-content">
								<i class="fa fa-star stars star-1"></i>
								<i class="fa fa-star stars star-2"></i>
								<i class="fa fa-star stars star-3"></i>
							</div>
							<img src="" id="buyHouse-image" style="z-index: 10" alt="product-pic" class="img-fluid">
							<div id="buyHouse-name" class="buyHouse-name"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row user-buildings">
		<div class="col-lg-3">
			<div class="user-houses-stats mb-2">
				<div>
					<div class="card card-body">
						<h5 class="card-title text-center">{{ __('Mint House') }}</h5>
						<p class="text-muted text-center">
							{{ __('Click on the button below to purchase a new home.') }}
						</p>
						<button v-on:click="doMint()" type="button" class="btn btn-primary waves-effect waves-light text-uppercase">
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
										<h3 class="text-dark mt-1"><span id="myCurrency">{{ currency(Auth::user()->currency) }}</span></h3>
										<p class="text-muted mb-1 text-truncate">{{ __('Coins') }}</p>
									</div>
								</div>
							</div><!-- end row-->
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
										<h3 class="text-dark mt-1"><span id="myBuildings">{{ $totalBuildings }}</span></h3>
										<p class="text-muted mb-1 text-truncate">{{ __('Total Houses') }}</p>
									</div>
								</div>
							</div><!-- end row-->
						</div>
					</div>
					<div class="widget-rounded-circle card">
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<div class="avatar-lg rounded-circle bg-soft-dark border-dark border">
										<i class="fe-dollar-sign font-22 avatar-title text-dark"></i>
									</div>
								</div>
								<div class="col-6">
									<div class="text-end">
										<h3 class="text-dark mt-1"><span id="myDailyClaim">{{ currency(Auth::user()->maxDailyClaim()) }}</span></h3>
										<p class="text-muted mb-1 text-truncate">{{ __('Max Daily Claim') }}</p>
									</div>
								</div>
							</div><!-- end row-->
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
										<h3 class="text-dark mt-1"><span id="myWorkers">{{ Auth::user()->workers() }}</span></h3>
										<p class="text-muted mb-1 text-truncate">{{ __('Total Citizens') }}</p>
									</div>
								</div>
							</div><!-- end row-->
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-9 building-list">
			<div class="row buildings-list">
				<template v-if="hasBuildings" v-for="building in buildingsFiltered" :key="building.id">
					<div class="col-md-6 col-xl-4">
						<div class="card ribbon-box">
							<div v-if="building.highlight" class="ribbon-two ribbon-two-blue text-uppercase"><span>{{ __('New') }}</span></div>
							<div class="card-body product-box" :class="{'building-hidden': building.hidden}">
								<div class="bg-sky text-center d-flex align-items-center justify-content-center" style="min-height: 340px; position: relative;">
									<img :src="building.image"  style="z-index: 10" :alt="building.name" class="img-fluid" />
								</div>

								<div class="product-info">
									<div class="row align-items-center">
										<div class="col">
											<h5 class="font-16 mt-0 sp-line-1">
												@{{ building.name }}
											</h5>
											<div class="text-warning mb-2 font-13">
												<i class="fa fa-star" v-for="x in building.rarity"></i>
											</div>
											<h5 class="m-0">
												<span class="text-muted">{{ __('Production') }}:
													<span :class="building.status.color">@{{ building.status.name }}</span>
												</span>
											</h5>
										</div>
										<div class="col-auto">
											<div class="product-price-tag">
												<i class="fa fa-users fa-fw"></i>
												@{{ building.level }}
											</div>
										</div>
									</div><!-- end row -->

									<div class="mt-1 text-center">
										<div class="button-list row mb-1">
											<button v-if="building.status.repair" v-on:click="doBuildRepair(building.id)" data-bs-toggle="tooltip" :title="'<b>{{ __('Cost') }}:</b> ' + building.status.cost" type="button" :data-id="building.id" class="col btn btn-danger waves-effect waves-light repair">
												<b>{{ __('Repair') }}</b>
											</button>
											<button v-if="!building.status.repair && building.upgrade" v-on:click="doBuildUpgrade(building.id)" data-bs-toggle="tooltip" :title="'<b>{{ __('Cost') }}:</b> ' + building.upgrade" type="button" :data-id="building.id" class="col btn btn-primary waves-effect waves-light upgrade">
												<b>{{ __('Upgrade') }}</b>
											</button>
											<button type="button" v-on:click="doBuildSell(building.id)" :data-id="building.id" class="col btn btn-dark waves-effect waves-light sell">
												<b>{{ __('Sell') }}</b>
											</button>
											<button type="button" v-on:click="doBuildClaim(building.id)" :data-id="building.id" class="col btn btn-success waves-effect waves-light claim" :disabled="!building.claim.enabled">
												<b>{{ __('Claim') }}</b>
											</button>
										</div>
										<small class="text-muted">
											<b>{{ __('House Vault') }}:</b>
											@{{ building.claim.progress }}% ( @{{ building.claim.available }} {{ __('Coins') }} )
										</small>
									</div>

									<div class="progress position-relative" style="height: 20px;">
										<div :class="building.claim.color + ' ' + (building.claim.progress < 100 ? 'progress-bar-animated' : '')" class="progress-bar progress-bar-striped" :style="'width:' + building.claim.progress + '%'"></div>
									</div>

									<div class="row text-center">
										<div class="col-4">
											<div class="mt-3">
												<h4>@{{ building.stats.daily }}</h4>
												<p class="mb-0 text-muted text-truncate">{{ __('Daily Claim') }}</p>
											</div>
										</div>
										<div class="col-4">
											<div class="mt-3">
												<h4>@{{ building.stats.last }}</h4>
												<p class="mb-0 text-muted text-truncate">{{ __('Last Claim') }}</p>
											</div>
										</div>
										<div class="col-4">
											<div class="mt-3">
												<h4>@{{ building.stats.total }}</h4>
												<p class="mb-0 text-muted text-truncate">{{ __('Total Claim') }}</p>
											</div>
										</div>
									</div>
								</div><!-- end product info-->
							</div>
						</div><!-- end card-->
					</div><!-- end col-->
				</template><!-- end row-->
				<template v-else>
					<div class="alert text-center col alert-danger bg-danger text-white border-0" role="alert">
						{{ __('Looks like you don\'t have any houses yet.') }}
						{{ __('But don\'t worry, you can purchase one at any time by clicking the "New Mint" button!') }}
					</div>
				</template>
			</div>
			<div class="text-end" v-if="hasBuildings && totalPages > 1">
				<ul class="pagination">
					<li class="page-item" :class="current_page <= 1 ? 'disabled' : ''" aria-disabled="true">
						<button class="page-link" v-on:click="nextPage(current_page - 1)"><i class="fe-arrow-left"></i></button>
					</li>
					<li class="page-item" :class="current_page >= totalPages ? 'disabled' : ''">
						<button class="page-link" v-on:click="nextPage(current_page + 1)" rel="next"><i class="fe-arrow-right"></i></button>
					</li>
				</ul>
			</div>
		</div>
	</div>
@endsection

@section('js-libs')
	<script src="https://unpkg.com/vue@next"></script>
@endsection
