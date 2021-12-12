<div class="row buildings-list">
	<template v-if="hasBuildings" v-for="building in buildingsFiltered" :key="building.id">
		<div class="col-md-6 col-xl-4">
			<div class="card ribbon-box">
				<div v-if="building.highlight" class="ribbon-two ribbon-two-blue text-uppercase">
					<span>{{ __('New') }}</span>
				</div>
				<div class="card-body product-box" :class="{'building-hidden': building.hidden}">
					<div class="bg-sky text-center d-flex align-items-center justify-content-center position-relative" style="min-height: 340px;">
						<img :src="building.image" style="z-index: 10;" :alt="building.name" class="img-fluid position-absolute bottom-0" />
					</div>

					<div class="product-info pt-1">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="font-16 mt-0 mb-0 sp-line-1">@{{ building.name }}</h5>
								<div class="text-warning mb-s2 font-13">
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

						<div class="mt-1s text-center">
							<div class="button-list d-flex mt-1">
								<button v-if="building.status.repair" v-on:click="doBuildRepair(building.id)" data-bs-toggle="tooltip" :title="'<b>{{ __('Cost') }}:</b> ' + building.status.cost" type="button" class="col btn btn-danger waves-effect waves-light btn-sm">
									{{ __('Repair') }}
								</button>
								<button v-if="!building.status.repair && building.upgrade" v-on:click="doBuildUpgrade(building.id)" data-bs-toggle="tooltip" :title="'<b>{{ __('Cost') }}:</b> ' + building.upgrade" type="button" class="col btn btn-primary waves-effect waves-light btn-sm">
									{{ __('Upgrade') }}
								</button>
								<button type="button" v-on:click="doBuildSell(building.id)" class="col btn btn-dark waves-effect waves-light btn-sm">
									{{ __('Sell') }}
								</button>
								<button type="button" v-on:click="building.claim.enabled && doBuildClaim(building.id)" :disabled="!building.claim.enabled" class="col btn btn-success waves-effect waves-light btn-sm">
									{{ __('Claim') }}
								</button>
							</div>

							<div class="mt-1">
								<small class="text-muted" v-if="building.claim.progress < 30">
									<b>{{ __('Next Claim Available') }}:</b>&nbsp;
									<span class="text-capitalize">@{{ building.claim.remaining }}</span>
								</small>
								<small class="text-success text-uppercase" v-else>
									<b>{{ __('Claim Available !!!') }}</b>
								</small>
							</div>
						</div>

						<div class="progress position-relative" style="height: 20px;">
							<div :class="building.claim.color + ' ' + (building.claim.progress < 100 ? 'progress-bar-animated' : '')" class="progress-bar progress-bar-striped" :style="'width:' + building.claim.progress + '%'"></div>
						</div>

						<div class="text-center mb-1">
							<small class="text-muted">
								<b>{{ __('House Vault') }}:</b>
								@{{ building.claim.progress }}% ( @{{ building.claim.available }} {{ __('Coins') }} )
							</small>
						</div>

						<div class="row text-center">
							<div class="col-4">
								<h4>@{{ building.stats.daily }}</h4>
								<p class="mb-0 text-muted text-truncate">{{ __('Daily Claim') }}</p>
							</div>
							<div class="col-4">
								<h4>@{{ building.stats.last }}</h4>
								<p class="mb-0 text-muted text-truncate">{{ __('Last Claim') }}</p>
							</div>
							<div class="col-4">
								<h4>@{{ building.stats.total }}</h4>
								<p class="mb-0 text-muted text-truncate">{{ __('Total Claim') }}</p>
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
<div class="paginator-end" v-if="hasBuildings && totalPages > 1">
	<ul class="pagination">
		<li class="page-item" :class="current_page <= 1 ? 'disabled' : ''" aria-disabled="true">
			<button class="page-link" v-on:click="nextPage(current_page - 1)"><i class="fe-arrow-left"></i></button>
		</li>
		<li class="page-item" :class="current_page >= totalPages ? 'disabled' : ''">
			<button class="page-link" v-on:click="nextPage(current_page + 1)" rel="next"><i class="fe-arrow-right"></i></button>
		</li>
	</ul>
</div>
