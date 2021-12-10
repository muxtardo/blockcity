@extends('partials/master')
@section('script')
<style>
	.bg-sky {
		background: #4B79A1;  /* fallback for old browsers */
		background: -webkit-linear-gradient(to top, #283E51, #0A2342);  /* Chrome 10-25, Safari 5.1-6 */
		background: linear-gradient(to top, #283E51, #0A2342); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
		  background: -olinear-gradient(to top, #283E51, #0A2342);
	}
	.bg-sky::before {
		content: '';
		background: url(http://www.script-tutorials.com/demos/360/images/stars.png) repeat center center;
		position:absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		width:100%;
		height:100%;
		display:block;
	}

.star-on {
	animation-fill-mode: forwards;
	animation-name: example;
  	animation-duration: 4s;
	  filter: filter: drop-shadow(0px 0px 0px yellow);
}
@keyframes example {
  from {color: #6c757d; filter: drop-shadow(0px 0px 0px yellow);}
  to {color: gold; filter: drop-shadow(0px 0px 5px yellow);}
}
.stars-content {
    position: absolute;
    top: 10px;
    left: 0;
}
.stars-content .star-1 {
    position: absolute;
    left: 20px;
    top: 50px;
    font-size: 40px;
}
.stars-content .star-2 {
    position: absolute;
    left: 110px;
    top: 5px;
    font-size: 60px;
}
.stars-content .star-3 {
    position: absolute;
    left: 210px;
    top: 50px;
    font-size: 40px;
}
.star-on {
	animation-fill-mode: forwards;
	animation-name: example;
  	animation-duration: 2s;
}
.buyHouse-name{
	position: absolute;
    left: 0;
    bottom: 0px;
    padding: 0.5rem;
    font-size: 2rem;
    background: rgb(0 0 0 / 89%);
    box-sizing: border-box;
    width: 100%;
    color: white;
    text-align: center;
}
#myModal .card-body, #myModal .modal-body {
	padding: 0px;
}
#buyHouse-image {
	margin-top: 100px;
}
</style>
<script>
	// bootstrap modal instance
	/*
	const myModal = new bootstrap.Modal(document.getElementById('myModal')); //new Modal(document.getElementById('myModal'));

	function showStarsAnimated(starsOnNumber) {
		const stars = document.querySelectorAll('.stars-content .stars');
		for (let i = 0; i < starsOnNumber; i++) {
			setTimeout(() => {
				stars[i].classList.add('star-on');
			}, i * 1000);
		}
	}
*/
/*
	$("#new-mint").click(async () => {
		const request = await axiosInstance.post('buildings/mint');
		const { image, name, rarity } = request.data;
		const starIcon = '<i class="fa fa-star stars"></i>';
		$("#buyHouse-name").text(name);
		$("#buyHouse-image").attr('src', image);
		myModal.show();
		showStarsAnimated(rarity);
		/*
		axiosInstance.post('buyHouse')
			.then((res) => {
				const starIcon = '<i class="fa fa-star d-none stars"></i>';
				$("#buyHouse-name").text(res.data.userItem.name);
				$("#buyHouse-image").attr('src', res.data.houseImage);
				$("#buyHouse-stars").html(starIcon.repeat(res.data.userItem.stars));
				myModal.show();
				showStarsAnimated();
			})
		

	});
*/
/*
	$("#page-item").on('click', async function(){
		const content = await axiosInstance.get('buildings');
		$(".buildings-list").html(content);		

	});
*/
</script>
<script src="https://unpkg.com/vue@next"></script>
<script>
const Counter = {
	data() {	
		return {
			counter: 0,
			buildings: [],
			current_page: 0,
			number_per_page: 6,
			total_page: 0,
			buildingsShow: [],
		}
	},
	methods: {
		async load_buildings(page = 1) {
			lockScreen(true);
			await axiosInstance.get('buildings', { params: { page } }).then((res) => {
				if (page == 1) {
					this.total_page = Math.ceil(res.data.counters.buildings / this.number_per_page) -1;
				}
				this.buildings.push(...res.data.buildings);
			});		
			lockScreen(false);	  
		},
		async reset_buildings() {
			this.buildings = [];
			await this.load_buildings();
			this.nextPage(0);
		},
		doBuildClaim(id) {
			this.doBuildAction('claim', id);
		},
		doBuildRepair(id) {
			this.doBuildAction('repair', id);
		},
		doBuildUpgrade(id) {
			this.doBuildAction('upgrade', id);		
		},
		doBuildSell(id){
			showAlert('Coming soon!', 'This feature is not available yet', 'info');
		},
		async doBuildAction(action, id){
			lockScreen(true);
			try {
				const response = await axiosInstance.post('/buildings/' + action, { id });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				this.reset_buildings();

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 3000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		},
		async nextPage(next) {
			this.current_page = next;
			let new_data = this.buildings.slice(this.current_page * this.number_per_page, (this.current_page+1) * this.number_per_page);
			if (new_data.length == 0) {
				await this.load_buildings(this.current_page + 1);
				new_data = this.buildings.slice(this.current_page * this.number_per_page, (this.current_page+1) * this.number_per_page);
			}
			console.log(new_data);
			this.buildingsShow.splice(0, this.number_per_page, ...new_data);
		},
		async doMint() {
			lockScreen(true);
			try {
				const request = await axiosInstance.post('buildings/mint');
				const building = request.data.building;
				this.buildings.splice(0, 0, building);		
				this.nextPage(0);
			} catch (e) {
				const { title, message } = e.response.data;
				showAlert(title, message, 'error');
			}
			lockScreen(false);
		},

	},
	async mounted() {
		await this.load_buildings();
		this.nextPage(0);
  	}
}

Vue.createApp(Counter).mount('.user-buildings')
</script>
@endsection
@section('content')
<div class="modal fade show" id="myModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div  class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<!-- Modal content-->
				<div class="card-body product-box">
					<div class="bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 340px;position: relative;">
						<div class="stars-content">
							<i class="fa fa-star stars star-1"></i>
							<i class="fa fa-star stars star-2"></i>
							<i class="fa fa-star stars star-3"></i>
						</div>
						<img src="http://127.0.0.1/assets/images/buildings/1/1.png" id="buyHouse-image" alt="product-pic" class="img-fluid">
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
					{{-- <p class="text-muted text-center">
						{{ __('Click on the button below to purchase a new home.') }}
					</p> --}}
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
									<h3 class="text-dark mt-1"><span id="myBuildings">{{ $totalBuildings }}</span></h3>
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
								<div class="avatar-lg rounded-circle bg-soft-dark border-dark border">
									<i class="fe-dollar-sign font-22 avatar-title text-dark"></i>
								</div>
							</div>
							<div class="col-6">
								<div class="text-end">
									<h3 class="text-dark mt-1"><span>{{ currency(Auth::user()->maxDailyClaim()) }}</span></h3>
									<p class="text-muted mb-1 text-truncate">{{ __('Max Daily Claim') }}</p>
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
									<h3 class="text-dark mt-1"><span id="myWorkers">{{ Auth::user()->workers() }}</span></h3>
									<p class="text-muted mb-1 text-truncate">{{ __('Total Citizens') }}</p>
								</div>
							</div>
						</div> <!-- end row-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 building-list">
		<div class="row buildings-list">
			<template v-if="buildings" v-for="building in buildingsShow" :key="building.id">
				<div class="col-md-6 col-xl-4">
					<div class="card ribbon-box">
						<div v-if="building.highlight" class="ribbon-two ribbon-two-blue text-uppercase"><span>{{ __('New') }}</span></div>
						<div class="card-body product-box">
							<div class="bg-sky text-center d-flex align-items-center justify-content-center" style="min-height: 340px; position: relative;">
								
								<img :src="building.image" :alt="building.name" class="img-fluid" />
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
								</div> <!-- end row -->

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
								
							</div> <!-- end product info-->
						</div>
					</div> <!-- end card-->
				</div> <!-- end col-->	
			</template> <!-- end row-->
			<template v-else>
				<div class="alert text-center col alert-danger bg-danger text-white border-0" role="alert">
					{{ __('Looks like you don\'t have any houses yet.') }}
					{{ __('But don\'t worry, you can purchase one at any time by clicking the "New Mint" button!') }}
				</div>
			</template>
		</div>
		<div class="text-end">
			<ul class="pagination">
				<li class="page-item" :class="current_page == 0 ? 'disabled' : ''" aria-disabled="true">
					<button class="page-link" v-on:click="nextPage(current_page - 1)"><i class="fe-arrow-left"></i></button>
				</li>
				<li class="page-item" :class="current_page == total_page ? 'disabled' : ''">
					<button class="page-link" v-on:click="nextPage(current_page + 1)" rel="next"><i class="fe-arrow-right"></i></button>
				</li>
			</ul>			
		</div>
	</div>
</div>
@endsection
