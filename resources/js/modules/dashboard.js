export default async function () {
	const { lockScreen, showAlert, enableTooltip } = require('../utils/global');
	const current_timestamp = Date.now();
	let instanceHidden = null;
	let instanceInterval = null;
	const myModal = new bootstrap.Modal(document.getElementById('myModal'));
	myModal._element.addEventListener('hidden.bs.modal', (event) => {
		$(".building-hidden").removeClass('building-hidden');
		$("body").css("overflow", "auto");
		instanceHidden.hidden = false;
	});

	function showNewMint(name, image, rarity) {
		const stars = document.querySelectorAll('#myModal .stars-content > .stars');
	
		$("#buyHouse-name").text(name);
		$("#buyHouse-image").attr('src', image);
	
		myModal.show();
	
		stars.forEach((star, index) => {
			star.classList.remove('star-on');
		});
	
		for (let i = 0; i < rarity; i++) {
			setTimeout(() => {
				stars[i].classList.add('star-on');
			}, i * 500);
		}
	}
	function updateBuildings(buildings) {
		const updateBuildingsData = () => {
			Object.values(buildings.value).forEach((building) => {
				const { last_claim_at, next_claim_at, current_time } = building.claim.times;
				const timeElapsed = (Date.now() - current_timestamp) / 1000;
				const t1 = (current_time + timeElapsed) - last_claim_at;
				const t2 = next_claim_at - last_claim_at;
				building.claim.progress = Math.min(100, t1 / t2 * 100).toFixed(2);
				building.claim.available = (building.stats.daily * building.claim.progress/100).toFixed(4);
				let claimColor = 'bg-success';
				if (building.claim.progress < 30) {
					claimColor = 'bg-warning';
				} else if (building.claim.progress >= 30 && building.claim.progress < 100) {
					claimColor = 'bg-primary';
				}
				building.claim.color = claimColor;
				building.claim.enabled = building.claim.progress >= 30;
				building.claim.remaining = moment((last_claim_at + ((24 * 3600) * ((config.min_claim) / 100))) * 1000).fromNow();
			});
		};
		updateBuildingsData();
		instanceInterval = setInterval(updateBuildingsData, 1000);
	}

	const buildingsApp = {
		data() {
			return {
				counter: 0,
				buildings: Vue.reactive({ value: {}}),
				current_page: 1,
				number_per_page: 6,
				total_mint: 0,
				last_load_page: 1,
				orderBy: 'claim_progress',
			}
		},
		methods: {
			changeOrderBy($event) {
				const orderBy = $event.target.value;
				localStorage.setItem('orderBy', orderBy);

				this.orderBy = orderBy;
				if (this.total_mint != Object.values(this.buildings.value).length) {
					this.last_load_page = 0;
				} else {
					this.last_load_page = Math.ceil(Object.values(this.buildings.value).length / this.number_per_page);
				}
				this.nextPage(1);
			},
			async load_buildings(page = 1) {
				lockScreen(true);

				const response = await axios.get('buildings', {
					params: { page, filter: this.orderBy }
				}).then(res => res.data);
				const { buildings, stats: {
					buildings: countBuildings,
					workers: countWorkers,
					currency: userCurrency,
					dailyClaim: userDailyClaim
				}} = response;
				if (page == 1) { this.total_mint = countBuildings; }

				$("#myCurrency").html(userCurrency);
				$("#myBuildings").html(countBuildings);
				$("#myDailyClaim").html(userDailyClaim);
				$("#myWorkers").html(countWorkers);

				lockScreen(false);
				const buildingsObject = buildings.reduce((acc, building) => ({...acc, [building.id]: building }), {});
				return buildingsObject;
			},
			doBuildClaim(id) {
				this.doBuildAction('claim', id);
			},
			doBuildRepair(id) {
				const cost = this.buildings.value[id].status.cost;
				this.doBuildAction('repair', id, cost);
			},
			doBuildUpgrade(id) {
				const cost = this.buildings.value[id].upgrade;
				this.doBuildAction('upgrade', id, cost);
			},
			doBuildSell(id) {
				showAlert(__("Coming soon!"), __("This feature is not available yet"), 'info');
			},
			async doBuildAction(action, id, cost = false){
				if (action != 'claim') {
					const confirmed = await Swal.fire({
						title: __("Are you sure?"),
						text: __("You are about to spend :currency coins to :action this house!", {action: __(action), currency: cost}),
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						cancelButtonText: __('Cancel'),
						confirmButtonText: __("Yes, :action it!", {action: __(action)}),
					}).then((result) => result.isConfirmed);

					if (!confirmed) { return; }
				}

				lockScreen(true);
				try {
					const response = await axios.post('buildings/' + action, { id });
					const { title, message, redirect, success, building, stats: {
						buildings: countBuildings,
						workers: countWorkers,
						currency: userCurrency,
						dailyClaim: userDailyClaim
					}} = response.data;

					$("#myCurrency").html(userCurrency);
					$("#myBuildings").html(countBuildings);
					$("#myDailyClaim").html(userDailyClaim);
					$("#myWorkers").html(countWorkers);

					this.buildings.value[id] = building;

					if (!this.loaded_all_buildings) {
						this.last_load_page = this.current_page - 1;
						this.nextPage(this.current_page);
					}

					showAlert(title, message, success ? 'success' : 'danger');

					if (redirect) {
						setTimeout(() => {
							window.location.href = redirect;
						}, 3000);
					}
				} catch (err) {
					const { title, message } = err.response.data;
					showAlert(title, message, 'error');
				} finally {
					lockScreen(false);
					enableTooltip();
				}
			},
			pageLoaded(number) {
				const pages_loaded = Math.ceil(Object.values(this.buildings.value).length / this.number_per_page);

				return this.last_load_page < number;
			},
			async nextPage(next) {
				clearInterval(instanceInterval);
				if (this.pageLoaded(next)) {
					this.last_load_page = next;
					lockScreen(true);

					const buildings = await this.load_buildings(next);
					this.buildings.value = Object.assign(this.buildings.value, buildings);

					lockScreen(false);
				}

				this.$nextTick(() => {
					this.current_page = next;
					updateBuildings(this.buildings);
				});
			},
			async doMint() {
				const confirmed = await Swal.fire({
					title: __("Are you sure?"),
					text: __("You are about to spend :currency coins to mint a new building!", {currency: config.mint_cost.toFixed(2)}),
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: __('Cancel'),
					confirmButtonText: __("Yes, :action it!", {action: __("Mint")}),
				}).then((result) => result.isConfirmed);

				if (!confirmed) { return; }

				lockScreen(true);
				try {
					const request = await axios.post('buildings/mint');
					const { building, stats: {
						buildings: countBuildings,
						workers: countWorkers,
						currency: userCurrency,
						dailyClaim: userDailyClaim
					}} = request.data;
					const { image, name, rarity } = building;
					building.hidden = true;
					instanceHidden = building;

					$("#myCurrency").html(userCurrency);
					$("#myBuildings").html(countBuildings);
					$("#myDailyClaim").html(userDailyClaim);
					$("#myWorkers").html(countWorkers);

					this.total_mint++;

					showNewMint(name, image, rarity);
					this.buildings.value[building.id] = building;
					this.nextPage(1);
				} catch (e) {
					const { title, message } = e.response.data;
					showAlert(title, message, 'error');
				} finally {
					lockScreen(false);
				}
			},
			orderBy_claim_progress(a, b) {
				return b.claim.progress - a.claim.progress;
			},
			orderBy_name(a, b) {
				return a.name.localeCompare(b.name);
			},
			orderBy_rarity(a, b) {
				return b.rarity - a.rarity;
			},
			orderBy_workers(a, b) {
				return a.workers - b.workers;
			},
			orderBy_currency(a, b) {
				return a.currency - b.currency;
			},
			orderBy_upgrade(a, b) {
				return a.upgrade - b.upgrade;
			},
			orderBy_level(a, b) {
				return b.level - a.level;
			},
			orderBy_status(a, b) {
				return b.status.loss - a.status.loss;
			},
			orderBy_highlight(fn) {
				return (a, b) => {
					switch (b.highlight + a.highlight) {
						case 0: return fn(a, b);
						case 1: return b.highlight - a.highlight;
						case 2: return b.id - a.id;
					}
				};
			},

		},
		async mounted() {
			if (localStorage.getItem('orderBy') !== null) {
				this.orderBy = localStorage.getItem('orderBy');
			}
			this.buildings.value = Object.assign(this.buildings.value, await this.load_buildings());
			this.nextPage(1);
			this.$nextTick(() => {
				updateBuildings(this.buildings);
			});
		},
		computed: {
			buildingsFiltered() {
				this.$nextTick(() => {
					enableTooltip();
				});
				const sorted	= Object.values(this.buildings.value).sort(this.orderBy_highlight(this['orderBy_' + this.orderBy]));
				const data		= sorted.slice((this.current_page - 1) * this.number_per_page, (this.current_page) * this.number_per_page);
				return data;
			},
			hasBuildings() {
				return Object.values(this.buildings.value).length > 0;
			},
			totalPages() {
				return Math.ceil(this.total_mint / this.number_per_page);
			},
			loaded_all_buildings() {
				return Object.values(this.buildings.value).length >= this.total_mint;
			},
		}
	}

	Vue.createApp(buildingsApp).mount('.user-buildings');
}
