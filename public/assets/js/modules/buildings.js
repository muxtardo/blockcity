(function () {
	const userBuildings = $('.user-buildings');
	if (userBuildings.length) {
		const myModal = new bootstrap.Modal(document.getElementById('myModal'));
		myModal._element.addEventListener('hidden.bs.modal', function (event) {
			$(".building-hidden").removeClass('building-hidden');
			$("body").css("overflow", "auto");
			instanceHidden.hidden = false;
		});

		const showNewMint = (name, image, rarity) => {
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
				}, i * 1000);
			}
		}

		let instanceHidden = null;
		const Counter = {
			data() {
				return {
					counter: 0,
					buildings: Vue.reactive({value: []}),
					current_page: 1,
					number_per_page: 6,
					total_mint: 0,
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
				doBuildUpgrade(id) {
					this.doBuildAction('upgrade', id);
				},
				doBuildSell(id){
					showAlert('Coming soon!', 'This feature is not available yet', 'info');
				},
				async doBuildAction(action, id){
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

					return pages_loaded < number;
				},
				async nextPage(next) {
					if (this.pageLoaded(next)) {
						lockScreen(true);

						const buildings = await this.load_buildings(next);
						this.buildings.value.push(...buildings);

						lockScreen(false);
					}
					this.$nextTick(() => {
						this.current_page = next;
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
