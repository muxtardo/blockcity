(function () {
	const userBuildings = $('.user-buildings');
	if (userBuildings.length) {
		const myModal = new bootstrap.Modal(document.getElementById('myModal'));
		myModal._element.addEventListener('hidden.bs.modal', function (event) {
			$(".building-hidden").removeClass('building-hidden');
			$("body").css("overflow", "auto");
			instanceHidden.hidden = false;
		});

		window.showNewMint = (name, image, rarity) => {
			const stars = document.querySelectorAll('.stars-content .stars');

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
			instanceInterval = setInterval(() => {
					buildings.value.forEach((building) => {
					const { last_claim_at, next_claim_at, current_time } = building.claim.times;
					t1 = current_time - last_claim_at;
					t2 = next_claim_at - last_claim_at;
					building.claim.times.current_time++;
					building.claim.progress = Math.min(100, t1 / t2 * 100).toFixed(2);
					building.claim.available = (building.stats.daily * building.claim.progress/100).toFixed(4);
				});
			}, 1000);	
		}

		let instanceHidden = null;
		let instanceInterval = null;
		const Counter = {
			data() {
				return {
					counter: 0,
					buildings: Vue.reactive({value: []}),
					current_page: 1,
					number_per_page: 6,
					total_mint: 0,
					last_load_page: 1,
					buildingsShow: [],
				}
			},
			methods: {
				async load_buildings(page = 1) {
					lockScreen(true);

					const response = await axiosInstance.get('buildings', { params: { page } }).then(res => res.data);
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
					return buildings;
				},
				doBuildClaim(id) {
					this.doBuildAction('claim', id);
				},
				doBuildRepair(id) {
					this.doBuildAction('repair', id);
				},
				async doBuildUpgrade(id) {
					this.doBuildAction('upgrade', id);
				},
				doBuildSell(id){
					showAlert('Coming soon!', 'This feature is not available yet', 'info');
				},
				async doBuildAction(action, id){
					if (action != 'claim') {
						const confirmed = await Swal.fire({
							title: 'Are you sure?',
							text: "You are about to spend :currency coins to " + action + " this house!".replace(':currency', this.buildings.value[id].upgrade),
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, ' + action + ' it!'
						}).then((result) => result.isConfirmed);
						
						if (!confirmed) { 
							return; 
						}
					}

					lockScreen(true);
					try {
						const response = await axiosInstance.post('buildings/' + action, { id });
						const { title, message, redirect, success, building, stats: {
							buildings: countBuildings,
							workers: countWorkers,
							currency: userCurrency,
							dailyClaim: userDailyClaim
						}} = response.data;

						lockScreen(false);

						$("#myCurrency").html(userCurrency);
						$("#myBuildings").html(countBuildings);
						$("#myDailyClaim").html(userDailyClaim);
						$("#myWorkers").html(countWorkers);

						this.buildings.value.splice(this.buildings.value.findIndex(building => building.id == id), 1, building);

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
				pageLoaded(number) {
					const pages_loaded = Math.ceil(this.buildings.value.length / this.number_per_page);

					return this.last_load_page < number;
				},
				async nextPage(next) {
					clearInterval(instanceInterval);
					if (this.pageLoaded(next)) {
						this.last_load_page = next;
						lockScreen(true);

						const buildings = await this.load_buildings(next);
						this.buildings.value.push(...buildings);

						lockScreen(false);
					}
					this.$nextTick(() => {
						this.current_page = next;
						updateBuildings(this.buildings);
					});
				},
				async doMint() {
					lockScreen(true);
					try {
						const request = await axiosInstance.post('buildings/mint');
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
						this.buildings.value.splice(0, 0, building);
						this.nextPage(1);
					} catch (e) {
						const { title, message } = e.response.data;
						showAlert(title, message, 'error');
					} finally {
						lockScreen(false);
					}
				},

			},
			async mounted() {
				this.buildings.value.push(...(await this.load_buildings()));
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
					return this.buildings.value.slice((this.current_page - 1) * this.number_per_page, (this.current_page) * this.number_per_page);
				},
				hasBuildings() {
					return this.buildings.value.length > 0;
				},
				totalPages() {
					return Math.ceil(this.total_mint / this.number_per_page);
				}
			}
		}

		Vue.createApp(Counter).mount('.user-buildings');
	}
})();
